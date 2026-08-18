<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
    /**
     * Real behavior confirmed live: called with NO date params (the page's initial
     * load, feeding the "Caja activa"/"Caja inactiva" status widget), it returns just
     * 1 row — the currently open caja, not the full history. Only when a real
     * fechaInicio/fechaFinal range is passed (the reports/list view) does it return
     * every caja in range. Previously this always returned full history, so the
     * untouched rescued JS's forEach (which sets #textstatus per row, last one wins)
     * could land on the wrong final state depending on row order — that's the real bug
     * behind the "estatus incorrecto" report, not the text itself (already correct).
     */
    public function getCajas(Request $request)
    {
        $inicio = $request->input('fechaInicio');
        $final = $request->input('fechaFinal');

        if (! $inicio && ! $final) {
            $abierta = Caja::where('status', 1)->orderByDesc('fecha_apertura')->first();
            $cajas = $abierta ? collect([$abierta]) : Caja::orderByDesc('fecha_apertura')->limit(1)->get();

            return response()->json(['cajas' => $cajas]);
        }

        $query = Caja::query();
        if ($inicio) {
            $query->whereDate('fecha_apertura', '>=', $inicio);
        }
        if ($final) {
            $query->whereDate('fecha_apertura', '<=', $final);
        }
        $cajas = $query->orderByDesc('fecha_apertura')->get();

        return response()->json(['cajas' => $cajas]);
    }

    /**
     * Dual-purpose endpoint matching the rescued JS (routeCajaStatus): the original
     * open-call posts only `status`; the close-call posts the cash-count reconciliation
     * fields (contadoEfectivo/contadoTransferencia/contadoTarjeta,
     * retiroEfectivo/retiroTransferencia/retiroTarjeta). We branch on which shape
     * arrived rather than using separate routes, to keep the untouched rescued JS working.
     */
    public function status(Request $request)
    {
        $userId = Auth::id();

        if ($request->has('contadoEfectivo')) {
            $caja = Caja::where('users_id', $userId)->where('status', 1)->latest('fecha_apertura')->first();
            if (! $caja) {
                return response()->json(['ok' => false, 'message' => 'No hay una caja abierta para cerrar.'], 422);
            }

            $contado = (float) $request->input('contadoEfectivo', 0)
                + (float) $request->input('contadoTransferencia', 0)
                + (float) $request->input('contadoTarjeta', 0);

            $caja->fecha_cierre = now();
            $caja->cuenta_final = $contado;
            $caja->status = 0;
            $caja->cierre_detalle = [
                'contado_efectivo' => (float) $request->input('contadoEfectivo', 0),
                'contado_transferencia' => (float) $request->input('contadoTransferencia', 0),
                'contado_tarjeta' => (float) $request->input('contadoTarjeta', 0),
                'retiro_efectivo' => (float) $request->input('retiroEfectivo', 0),
                'retiro_transferencia' => (float) $request->input('retiroTransferencia', 0),
                'retiro_tarjeta' => (float) $request->input('retiroTarjeta', 0),
                'diferencia' => $contado - (float) $caja->cuenta_inicial,
            ];
            $caja->save();

            return response()->json(['ok' => true, 'caja' => $caja]);
        }

        $yaAbierta = Caja::where('users_id', $userId)->where('status', 1)->exists();
        if ($yaAbierta) {
            return response()->json(['ok' => false, 'message' => 'Ya tienes una caja abierta.'], 422);
        }

        $caja = Caja::create([
            'locales_id' => Auth::user()->locales_id ?? 1,
            'users_id' => $userId,
            'turno' => now()->hour < 15 ? 'MATUTINO' : 'VESPERTINO',
            'cuenta_inicial' => (float) $request->input('efectivo', 0),
            'fecha_apertura' => now(),
            'status' => 1,
            'transacciones' => [],
        ]);

        return response()->json(['ok' => true, 'caja' => $caja]);
    }

    private function cajaAbierta()
    {
        return Caja::where('users_id', Auth::id())->where('status', 1)->latest('fecha_apertura')->first();
    }

    /** Real route: POST /deposito (fields referencia, monto, tipoDeposito 0/1/2=Efectivo/Transferencia/Tarjeta). */
    public function deposito(Request $request)
    {
        return $this->registrarMovimiento($request, 'deposito', (int) $request->input('tipoDeposito'));
    }

    /** Real route: POST /retiro (fields referencia, monto, tipoRetiro 0/1/2=Efectivo/Transferencia/Tarjeta). */
    public function retiro(Request $request)
    {
        return $this->registrarMovimiento($request, 'retiro', (int) $request->input('tipoRetiro'));
    }

    private function registrarMovimiento(Request $request, string $movimiento, int $tipoPago)
    {
        $caja = $this->cajaAbierta();
        if (! $caja) {
            return response()->json(['ok' => false, 'message' => 'No tienes una caja abierta.'], 422);
        }

        $transacciones = $caja->transacciones ?? [];
        $transacciones[] = [
            'id' => count($transacciones) + 1,
            'caja_id' => $caja->id,
            'tipo_transaccion' => $movimiento === 'deposito' ? 1 : 2,
            'tipo_pago' => $tipoPago,
            'monto' => (float) $request->input('monto'),
            'descripcion' => $request->input('referencia'),
            'usuario_id' => Auth::id(),
            'movimiento' => $movimiento,
            'referencia' => $request->input('referencia'),
            'created_at' => now()->toIso8601String(),
        ];
        $caja->transacciones = $transacciones;
        $caja->save();

        return response()->json(['ok' => true, 'caja' => $caja]);
    }

    /**
     * Real route: POST /getDatosCierreCaja. Feeds the cierre-de-caja modal's
     * prefilled "calculado" totals. Real limitation: sales in this build don't record
     * a per-payment-method split (efectivo/tarjeta/transferencia), only a total — so
     * "calculado" here is the sum of deposito/retiro movements recorded during this
     * caja session by tipo_pago (cuenta_inicial counted as efectivo), not derived from
     * sales. Documented simplification, not fabricated data.
     */
    public function getDatosCierreCaja(Request $request)
    {
        $caja = $this->cajaAbierta();
        if (! $caja) {
            return response()->json(['error' => 'No tienes una caja abierta.'], 422);
        }

        $totales = [0 => (float) $caja->cuenta_inicial, 1 => 0.0, 2 => 0.0];
        foreach ($caja->transacciones ?? [] as $t) {
            $signo = ($t['movimiento'] ?? '') === 'retiro' ? -1 : 1;
            $tipoPago = (int) ($t['tipo_pago'] ?? 0);
            if (isset($totales[$tipoPago])) {
                $totales[$tipoPago] += $signo * (float) $t['monto'];
            }
        }

        return response()->json([
            'totalEfectivo' => $totales[0],
            'totalTransferencia' => $totales[1],
            'totalTarjeta' => $totales[2],
            'recargas' => false,
        ]);
    }
}
