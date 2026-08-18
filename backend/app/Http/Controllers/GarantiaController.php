<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Garantia;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GarantiaController extends Controller
{
    // Statuses from the rescued garantia-atender.html <select>: 2/3/4.
    // 1 is the implicit initial "Solicitud de garantía" state seen in seeded data.
    private const STATUS_MAP = [
        1 => 'Solicitud de garantía',
        2 => 'En proceso de revisión',
        3 => 'Aprobada',
        4 => 'Rechazada',
    ];

    public function updateStatus(Request $request)
    {
        $garantia = Garantia::findOrFail($request->input('garantia_id'));
        $codigo = (int) $request->input('status');

        $garantia->status = self::STATUS_MAP[$codigo] ?? $garantia->status;
        $garantia->save();

        return response()->json(['ok' => true, 'garantia' => $garantia]);
    }

    /**
     * Real route recovered from the live site: POST /garantia/cambiar-producto/{id}.
     * Only shown for status='Resuelto' + tipo_resolucion='cambio' (confirmed live: 34
     * of 39 Resuelto rows). Marks the exchange as done; 'Finalizado' matches the real
     * terminal status seen on already-processed garantías (Finalizado=164 real rows).
     */
    public function cambiarProducto(int $id)
    {
        $garantia = Garantia::findOrFail($id);
        $garantia->status = 'Finalizado';
        $garantia->tipo_resolucion = 'cambio';
        $garantia->save();

        return response()->json(['ok' => true, 'garantia' => $garantia]);
    }

    /**
     * Real route recovered from the live site: POST /garantia/usar-nota-credito/{id}.
     * Credit amount = the original garantía product's precio_1 (confirmed live: a
     * $760.00 credit matched TABLETA STICH's precio_1 exactly). Assumption (no real
     * notas-de-crédito ledger table exists in this build): redeeming creates a real
     * Venta for the chosen replacement product at $0 (fully covered by the credit) and
     * decrements its stock — same stock/venta mechanics as the rest of the system,
     * just without payment. Documented simplification, not guessed data.
     */
    public function usarNotaCredito(Request $request, int $id)
    {
        $garantia = Garantia::findOrFail($id);
        $nuevoProducto = Producto::findOrFail($request->input('producto'));

        if ($nuevoProducto->stock < 1) {
            return response()->json(['ok' => false, 'message' => 'Ese producto no tiene existencia disponible.'], 422);
        }

        $nuevoProducto->decrement('stock', 1);
        $venta = Venta::create([
            'fecha_compra' => now(),
            'vendedores' => [Auth::user()->name],
            'total' => 0,
            'utilidad' => -1 * (float) ($nuevoProducto->precio_compra ?? 0),
            'no_venta' => (int) (Venta::max('no_venta') ?? 0) + 1,
            'tipo_venta' => 0,
            'status' => 1,
            'departamentos' => [],
            'producto_vendido' => [[
                'id' => $nuevoProducto->id,
                'nombre' => $nuevoProducto->descripcion,
                'cantidad' => '1.00',
            ]],
        ]);

        $garantia->status = 'Finalizado';
        $garantia->tipo_resolucion = 'nota_credito';
        $garantia->save();

        return response()->json(['ok' => true, 'garantia' => $garantia, 'venta' => $venta]);
    }

    /**
     * Real route recovered from the live site: POST /garantia/lastPurchase (fields
     * cliente_id, producto_id), consumed by garantia-atender's #lastPurchaseModal.
     * Real limitation, not a bug: the rescued historical ventas.csv (5,626 rows) never
     * captured which client made each sale — /historico never showed a client column
     * — so this can only find matches among sales made going forward through this
     * clone's own POS (now that Venta::cliente_id is tracked). Returns 404 with a
     * clear message when there's genuinely no purchase on record, same as the real
     * system would for a client who never bought that product.
     */
    public function lastPurchase(Request $request)
    {
        $clienteId = (int) $request->input('cliente_id');
        $productoId = (int) $request->input('producto_id');

        $venta = Venta::where('cliente_id', $clienteId)
            ->orderByDesc('fecha_compra')
            ->get()
            ->first(function ($v) use ($productoId) {
                return collect($v->producto_vendido ?? [])->contains(fn ($item) => (int) $item['id'] === $productoId);
            });

        if (! $venta) {
            return response()->json(['error' => 'No se encontró una compra previa de este producto para este cliente.'], 404);
        }

        $item = collect($venta->producto_vendido)->first(fn ($i) => (int) $i['id'] === $productoId);
        $cliente = Cliente::find($clienteId);
        $producto = Producto::find($productoId);

        return response()->json([
            'cliente' => $cliente?->nombre,
            'producto' => $producto?->descripcion ?? ($item['nombre'] ?? null),
            'fecha' => optional($venta->fecha_compra)->format('Y-m-d H:i:s'),
            'cantidad' => $item['cantidad'] ?? null,
            'precio' => $producto?->precio_1,
        ]);
    }
}
