<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Servicio;
use App\Models\ServicioCobro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Pago de recibos/recargas subsystem. Real routes confirmed from js/app-pdv.js
 * (punto-de-venta's #formCobroServicio) and js/app-caja.js (caja's "RECAUDACIÓN PAGO
 * SERVICIOS" button), captured 2026-08-18. See the 2026_08_18_000003 migration for the
 * exact field-shape evidence behind servicio_cobros.
 */
class ServicioController extends Controller
{
    /** Real route: POST /servicios/getServicios. Response: {servicios:[{id,nombre}]}. */
    public function getServicios()
    {
        return response()->json(['servicios' => Servicio::where('status', 1)->orderBy('nombre')->get(['id', 'nombre'])]);
    }

    /**
     * Real route: POST /servicios/cobroServicio (#formCobroServicio, fields servicio_id,
     * efectivo, transferencia, referencia). Registers the payment against the currently
     * open caja, pending end-of-day recaudación.
     */
    public function cobroServicio(Request $request)
    {
        $servicio = Servicio::findOrFail($request->input('servicio_id'));
        $caja = Caja::where('users_id', Auth::id())->where('status', 1)->latest('fecha_apertura')->first();

        ServicioCobro::create([
            'servicio_id' => $servicio->id,
            'caja_id' => $caja?->id,
            'users_id' => Auth::id(),
            'efectivo' => (float) $request->input('efectivo', 0),
            'transferencia' => (float) $request->input('transferencia', 0),
            'referencia' => $request->input('referencia'),
            'fecha_pago' => now(),
            'status' => 0,
        ]);

        return response()->json(['ok' => true, 'message' => 'Servicio de cobro realizado.']);
    }

    /**
     * Real route: POST /servicios/recaudacion (caja's "RECAUDACIÓN PAGO SERVICIOS" button,
     * window.recaudacion, no request fields). Real response shape confirmed from
     * js/app-caja.js: {cobros:[{id,efectivo,transferencia,cantidad_retiro,
     * cantidad_diferencia,fecha_pago,servicio:{nombre}}]} — lists every service payment
     * still pending recaudación (not filtered by caja, since the modal reconciles the
     * whole day regardless of which caja session took each payment).
     */
    public function recaudacion()
    {
        $caja = Caja::where('users_id', Auth::id())->where('status', 1)->latest('fecha_apertura')->first();
        if (! $caja) {
            return response()->json(false);
        }

        $cobros = ServicioCobro::with('servicio')->where('status', 0)->orderBy('fecha_pago')->get();

        return response()->json(['cobros' => $cobros]);
    }

    /**
     * Real route: POST /servicios/finalizarRecaudacion (window.finalizarRecaudacion, field
     * items = JSON [{id,efectivo,transferencia,retiro,diferencia}]). Real JS checks the raw
     * response truthiness (`t?...:toast("No se encontró caja activa")`), so failure returns
     * a literal `false` JSON body rather than the {ok:false} convention used elsewhere.
     */
    public function finalizarRecaudacion(Request $request)
    {
        $caja = Caja::where('users_id', Auth::id())->where('status', 1)->latest('fecha_apertura')->first();
        if (! $caja) {
            return response()->json(false);
        }

        $items = json_decode($request->input('items', '[]'), true) ?: [];
        $totalRetiro = 0;
        foreach ($items as $item) {
            $cobro = ServicioCobro::find($item['id'] ?? null);
            if (! $cobro) {
                continue;
            }
            $cobro->cantidad_retiro = (float) ($item['retiro'] ?? 0);
            $cobro->cantidad_diferencia = (float) ($item['diferencia'] ?? 0);
            $cobro->status = 1;
            $cobro->save();
            $totalRetiro += (float) ($item['retiro'] ?? 0);
        }

        if ($totalRetiro > 0) {
            $transacciones = $caja->transacciones ?? [];
            $transacciones[] = [
                'id' => count($transacciones) + 1,
                'caja_id' => $caja->id,
                'tipo_transaccion' => 1,
                'tipo_pago' => 0,
                'monto' => $totalRetiro,
                'descripcion' => 'Recaudación de pago de servicios',
                'usuario_id' => Auth::id(),
                'movimiento' => 'deposito',
                'referencia' => 'Recaudación de servicios',
                'created_at' => now()->toIso8601String(),
            ];
            $caja->transacciones = $transacciones;
            $caja->save();
        }

        return response()->json(['ok' => true]);
    }
}
