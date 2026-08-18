<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Traspaso;
use App\Models\TraspasoDetalle;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Inter-branch transfer requests + shipment tracking (requisiciones/traspasos entre
 * sucursales — the piece deliberately left out of Phase 3 for time). Route names
 * match the real ones found in js/app-inventario.js so the untouched rescued JS
 * (jspdv/pedidos.js's Pedidos class + the modal_traspaso / modal_movimiento_mercancia
 * modals in mi-local/productos.html) keeps working unmodified.
 *
 * Simplification, same spirit as Phase 3's single-step "compras": this build has one
 * shared Producto.stock pool (no real per-branch stock table), so "sucursales" reuses
 * the existing Auditoria (branches) table rather than a new one, and stock is
 * decremented on envío / incremented on recepción against that single pool — modeling
 * "in transit" correctly even though there's no real per-branch split to move between.
 */
class TraspasoController extends Controller
{
    // status machine
    private const SOLICITADO = 0;
    private const AUTORIZADO = 1;
    private const ENVIADO = 2;
    private const RECIBIDO = 3;
    private const RECHAZADO = 4;
    private const CANCELADO = 5;

    public function getTiendasVinculadas()
    {
        return response()->json(['sucursales' => Auditoria::orderBy('nombre')->get(['id', 'nombre'])]);
    }

    public function crearSolicitud(Request $request)
    {
        $sucursalId = $request->input('sucursal_id');
        $productos = json_decode($request->input('productos', '[]'), true) ?: [];

        if (! $sucursalId || empty($productos)) {
            return response()->json(['status' => 0, 'message' => 'Selecciona una sucursal y al menos un producto.']);
        }

        $noRequisicion = (int) (Traspaso::max('id') ?? 0) + 1;

        $traspaso = DB::transaction(function () use ($sucursalId, $productos, $noRequisicion) {
            $traspaso = Traspaso::create([
                'sucursal_origen_id' => $sucursalId,
                'sucursal_destino_id' => null,
                'status' => self::SOLICITADO,
                'users_id' => Auth::id(),
                'no_requisicion' => $noRequisicion,
            ]);

            foreach ($productos as $item) {
                if (empty($item['id'])) {
                    continue;
                }
                TraspasoDetalle::create([
                    'traspaso_id' => $traspaso->id,
                    'producto_id' => $item['id'],
                    'cantidad_solicitada' => (float) ($item['cantidad'] ?? 0),
                ]);
            }

            return $traspaso;
        });

        return response()->json(['status' => 1, 'message' => 'Solicitud de traspaso registrada.', 'requisicion' => $traspaso]);
    }

    /**
     * Real evidence: js/app-inventario.js's #activos/#surtidos tabs on mi-local/productos
     * feed setPedidosActivos/setPedidosSurtidos from THIS SAME getRequisicionesActivas/
     * getRequisicionesSurtidas pair (routeGetRequisicionesActivas, verbatim) — so compras
     * pedidos (proveedor_id set) and inter-branch traspasos (sucursal_origen_id set) are
     * listed together here, not via a separate compras endpoint.
     */
    private function baseList($statuses)
    {
        return Traspaso::whereIn('status', $statuses)
            ->orderByDesc('id')
            ->get()
            ->map(function ($t) {
                if ($t->proveedor_id) {
                    $proveedor = Proveedor::find($t->proveedor_id);

                    return [
                        'id' => $t->id,
                        'proveedor' => $proveedor?->nombre ?? 'Proveedor #'.$t->proveedor_id,
                        'no_requisicion' => $t->no_requisicion,
                        'status' => $t->status,
                        'celular' => $proveedor?->celular,
                    ];
                }

                $sucursal = Auditoria::find($t->sucursal_origen_id);

                return [
                    'id' => $t->id,
                    'proveedor' => $sucursal?->nombre ?? 'Sucursal #'.$t->sucursal_origen_id,
                    'no_requisicion' => $t->no_requisicion,
                    'status' => $t->status,
                    'celular' => $sucursal?->telefono,
                ];
            });
    }

    public function getRequisicionesActivas()
    {
        return response()->json(['requisiciones' => $this->baseList([self::SOLICITADO, self::AUTORIZADO, self::ENVIADO])]);
    }

    public function getRequisicionesSurtidas()
    {
        return response()->json(['requisiciones' => $this->baseList([self::RECIBIDO, self::RECHAZADO, self::CANCELADO])]);
    }

    /**
     * Real route: POST /verRequisicion (field id = pedido/traspaso id). Real shape
     * confirmed from BOTH callers of routeVerRequisicion in js/app-inventario.js
     * (traspasos' own window.verRequisicion AND compras' window.arribo_productos): a FLAT
     * ARRAY of line items with id (= traspaso_detalle id, used later by
     * modal_change_cantidad_arribo/finalizarCompra), descripcion, cantidad_solicitada,
     * cantidad_comprada, precio_compra — not the nested {id,detalles:[...]} shape this
     * returned before (that mismatch meant the real rescued JS's .forEach on the response
     * would have silently iterated object keys instead of items — fixed here).
     */
    public function verRequisicion(Request $request)
    {
        $traspaso = Traspaso::with('detalles.producto')->findOrFail($request->input('id'));

        $items = $traspaso->detalles->map(function ($d) use ($traspaso) {
            $cantidadComprada = $d->cantidad_comprada ?? $d->cantidad_enviada ?? $d->cantidad_solicitada;
            $precioCompra = $d->costo_unitario ?? $d->producto?->precio_compra ?? 0;

            return [
                'id' => $d->id,
                'producto_id' => $d->producto_id,
                'descripcion' => $d->producto?->descripcion,
                'cantidad_solicitada' => $d->cantidad_solicitada,
                'cantidad_comprada' => $cantidadComprada,
                'precio_compra' => $precioCompra,
            ];
        });

        return response()->json(['requisicion' => $items]);
    }

    public function updateCantidadSolicitada(Request $request)
    {
        $detalle = TraspasoDetalle::findOrFail($request->input('detalle_id'));
        $detalle->cantidad_solicitada = (float) $request->input('cantidad');
        $detalle->save();

        return response()->json(['status' => 1, 'detalle' => $detalle]);
    }

    public function autorizar(Request $request)
    {
        $traspaso = Traspaso::findOrFail($request->input('id'));
        $traspaso->status = self::AUTORIZADO;
        $traspaso->save();

        return response()->json(['status' => true, 'typeMessage' => 'Success', 'message' => 'Solicitud autorizada.', 'requisicion' => $traspaso]);
    }

    public function getMovimientoMercancia()
    {
        return response()->json(['movimientos' => $this->baseList([self::AUTORIZADO, self::ENVIADO, self::RECIBIDO, self::RECHAZADO])]);
    }

    public function getMovimientoMercanciaDetalles(Request $request)
    {
        $traspaso = Traspaso::with('detalles.producto')->findOrFail($request->input('id'));

        return response()->json(['detalles' => $traspaso->detalles]);
    }

    public function enviarMovimientoMercancia(Request $request)
    {
        $traspaso = Traspaso::with('detalles')->findOrFail($request->input('id'));
        if ($traspaso->status !== self::AUTORIZADO) {
            return response()->json(['status' => false, 'typeMessage' => 'Error', 'message' => 'Solo se puede enviar una solicitud ya autorizada.']);
        }

        DB::transaction(function () use ($traspaso) {
            foreach ($traspaso->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if (! $producto) {
                    continue;
                }
                $cantidad = min((float) $detalle->cantidad_solicitada, (float) $producto->stock);
                $detalle->cantidad_enviada = $cantidad;
                $detalle->save();
                $producto->decrement('stock', $cantidad);
            }
            $traspaso->status = self::ENVIADO;
            $traspaso->save();
        });

        return response()->json(['status' => true, 'typeMessage' => 'Success', 'message' => 'Mercancía enviada.', 'requisicion' => $traspaso]);
    }

    public function ingresarMovimientoMercanciaDetalles(Request $request)
    {
        $traspaso = Traspaso::with('detalles')->findOrFail($request->input('id'));
        if ($traspaso->status !== self::ENVIADO) {
            return response()->json(['status' => false, 'typeMessage' => 'Error', 'message' => 'Solo se puede recibir mercancía ya enviada.']);
        }

        DB::transaction(function () use ($traspaso) {
            foreach ($traspaso->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                $cantidad = (float) ($detalle->cantidad_enviada ?? $detalle->cantidad_solicitada);
                $detalle->cantidad_recibida = $cantidad;
                $detalle->save();
                $producto?->increment('stock', $cantidad);
            }
            $traspaso->status = self::RECIBIDO;
            $traspaso->save();
        });

        return response()->json(['status' => true, 'typeMessage' => 'Success', 'message' => 'Mercancía recibida, stock actualizado.', 'requisicion' => $traspaso]);
    }

    public function rechazarMovimientoMercanciaDetalles(Request $request)
    {
        $traspaso = Traspaso::findOrFail($request->input('id'));
        $traspaso->status = self::RECHAZADO;
        $traspaso->save();

        return response()->json(['status' => true, 'typeMessage' => 'Success', 'message' => 'Traspaso rechazado.', 'requisicion' => $traspaso]);
    }

    /**
     * Real route: POST /descargarTraspasoPdf (mi-local/productos' #form_id, plain form
     * submit, field id = traspaso id). Real PDF via barryvdh/laravel-dompdf, covers both
     * traspasos entre sucursales and compras a proveedor (same shared table).
     */
    public function descargarPdf(Request $request)
    {
        $traspaso = Traspaso::with('detalles.producto', 'proveedor')->findOrFail($request->input('id'));
        $pdf = Pdf::loadView('reportes.traspaso_pdf', ['traspaso' => $traspaso]);

        return $pdf->download('movimiento-'.$traspaso->id.'.pdf');
    }

    public function eliminarMovimientoMercancia(Request $request)
    {
        $traspaso = Traspaso::findOrFail($request->input('id'));
        if (in_array($traspaso->status, [self::ENVIADO, self::RECIBIDO], true)) {
            return response()->json(['status' => false, 'typeMessage' => 'Error', 'message' => 'No se puede eliminar un traspaso ya enviado o recibido.']);
        }
        $traspaso->detalles()->delete();
        $traspaso->delete();

        return response()->json(['status' => true, 'typeMessage' => 'Success', 'message' => 'Solicitud eliminada.']);
    }
}
