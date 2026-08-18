<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Compra;
use App\Models\Departamento;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Traspaso;
use App\Models\TraspasoDetalle;
use App\Models\UnidadCompra;
use App\Models\UnidadVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComprasController extends Controller
{
    // Shared with TraspasoController's status machine (same table, see the
    // 2026_08_18_000002 migration's note on why compras reuse traspasos).
    private const SOLICITADO = 0;

    private const AUTORIZADO = 1;

    private const RECIBIDO = 3;

    // --- Proveedores ---

    public function addProveedor(Request $request)
    {
        $proveedor = Proveedor::create([
            'nombre' => $request->input('nombre'),
            'representante' => $request->input('representante'),
            'celular' => $request->input('celular'),
            'telefono' => $request->input('telefono'),
            'emails' => $request->input('emails'),
            'status' => 1,
        ]);

        return response()->json(['proveedor' => $proveedor]);
    }

    public function getProveedores()
    {
        return response()->json(['proveedores' => Proveedor::orderBy('nombre')->get()]);
    }

    public function statusProveedor(Request $request)
    {
        $proveedor = Proveedor::findOrFail($request->input('id'));
        $proveedor->status = (int) $request->input('status');
        $proveedor->save();

        return response()->json(['ok' => true, 'proveedor' => $proveedor]);
    }

    // --- Departamentos / categorías ---

    public function storeDepartamento(Request $request)
    {
        $departamento = Departamento::create([
            'departamento' => $request->input('departamento'),
            'status' => 1,
        ]);

        return response()->json(['status' => true, 'departamento' => $departamento]);
    }

    public function statusDepto(Request $request)
    {
        $departamento = Departamento::findOrFail($request->input('id'));
        $departamento->status = (int) $request->input('status');
        $departamento->save();

        return response()->json(['ok' => true, 'departamento' => $departamento]);
    }

    public function storeCategoria(Request $request)
    {
        $categoria = Categoria::create([
            'departamentos_id' => $request->input('departamentos_id'),
            'categoria' => $request->input('categoria'),
        ]);

        return response()->json(['categoria' => $categoria, 'depto_id' => $categoria->departamentos_id]);
    }

    public function deleteCategoria(Request $request)
    {
        Categoria::where('id', $request->input('id'))->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Real route: POST /get_datos_generales. Also returns unidades_compra/unidades_venta
     * (feeds the edit-producto unit selects, see js/app-inventario.js's success handler:
     * $.each(t.unidades_compra,...)/$.each(t.unidades_venta,...)) and pedidos (count of
     * pending compras-a-proveedor, feeds #total_pedidos on mi-local/productos).
     */
    public function getDatosGenerales()
    {
        return response()->json([
            'departamentos' => Departamento::orderBy('departamento')->get(),
            'categorias' => Categoria::all(),
            'proveedores' => Proveedor::orderBy('nombre')->get(),
            'unidades_compra' => UnidadCompra::orderBy('unidad_compra')->get(),
            'unidades_venta' => UnidadVenta::orderBy('unidad_venta')->get(),
            'pedidos' => Traspaso::whereNotNull('proveedor_id')->whereIn('status', [self::SOLICITADO, self::AUTORIZADO])->count(),
        ]);
    }

    /**
     * Real route: POST /setProductosRequisicion (#btn_pedido, fields proveedores_id +
     * productos JSON [{id,cantidad}]). Phase 1 of the real 2-phase compra flow: registers
     * a pending pedido with NO payment info yet (pay-on-arrival), status=SOLICITADO.
     * Shares the traspasos table (proveedor_id set instead of sucursal_origen_id) — see
     * the 2026_08_18_000002 migration note for why.
     */
    public function setProductosRequisicion(Request $request)
    {
        return $this->crearPedido($request, false);
    }

    /**
     * Real route: POST /productos.comprar (#btn_comprar, fields proveedores_id + productos
     * JSON [{id,cantidad,costo}] + efectivo/transferencia/tarjeta). Phase 1 variant that
     * pre-pays the proveedor at order time; still starts as a pending pedido awaiting
     * arribo/recepción (phase 2, ComprasController::finalizarCompra), same as
     * setProductosRequisicion.
     */
    public function comprar(Request $request)
    {
        return $this->crearPedido($request, true);
    }

    private function crearPedido(Request $request, bool $conPago)
    {
        $proveedorId = (int) $request->input('proveedores_id', 0);
        $productos = json_decode($request->input('productos', '[]'), true) ?: [];
        if (empty($productos)) {
            return response()->json(['ok' => false, 'message' => 'Agregue productos al pedido.'], 422);
        }

        $noRequisicion = (int) (Traspaso::max('id') ?? 0) + 1;

        $pedido = DB::transaction(function () use ($proveedorId, $productos, $noRequisicion, $conPago, $request) {
            $pedido = Traspaso::create([
                'sucursal_origen_id' => null,
                'sucursal_destino_id' => null,
                'proveedor_id' => $proveedorId ?: null,
                'status' => self::SOLICITADO,
                'users_id' => Auth::id(),
                'no_requisicion' => $noRequisicion,
                'efectivo' => $conPago ? (float) $request->input('efectivo', 0) : null,
                'transferencia' => $conPago ? (float) $request->input('transferencia', 0) : null,
                'tarjeta' => $conPago ? (float) $request->input('tarjeta', 0) : null,
            ]);

            foreach ($productos as $item) {
                if (empty($item['id'])) {
                    continue;
                }
                TraspasoDetalle::create([
                    'traspaso_id' => $pedido->id,
                    'producto_id' => $item['id'],
                    'cantidad_solicitada' => (float) ($item['cantidad'] ?? 0),
                    'costo_unitario' => isset($item['costo']) ? (float) $item['costo'] : null,
                ]);
            }

            return $pedido;
        });

        return response()->json(['ok' => true, 'message' => 'Pedido registrado.', 'pedido' => $pedido]);
    }

    /** Real route: POST /autorizarCompra (field id = pedido id). Mirrors TraspasoController::autorizar. */
    public function autorizarCompra(Request $request)
    {
        $pedido = Traspaso::whereNotNull('proveedor_id')->findOrFail($request->input('id'));
        $pedido->status = self::AUTORIZADO;
        $pedido->save();

        return response()->json(['estado' => $pedido->status, 'pedido' => $pedido]);
    }

    /**
     * Real route: POST /finalizarCompra (#btn_finaliza_compra, field productos = JSON array
     * of {id (= traspaso_detalle id, per TraspasoController::verRequisicion's flat shape),
     * cantidad_comprada (possibly adjusted via modal_change_cantidad_arribo)}). Phase 2:
     * receives the pedido, adds the (possibly adjusted) counted quantity to stock, closes
     * the pedido. The real JS never sends the pedido id explicitly (it's tracked only
     * client-side via Pedidos.arriboId) — inferred here from the first detalle's traspaso_id,
     * documented assumption since it couldn't be confirmed against the live site this pass.
     */
    public function finalizarCompra(Request $request)
    {
        $items = json_decode($request->input('productos', '[]'), true) ?: [];
        if (empty($items)) {
            return response()->json(['ok' => false, 'message' => 'No hay productos para recibir.'], 422);
        }

        $primerDetalle = TraspasoDetalle::find($items[0]['id'] ?? null);
        if (! $primerDetalle) {
            return response()->json(['ok' => false, 'message' => 'Pedido no encontrado.'], 422);
        }
        $pedido = Traspaso::whereNotNull('proveedor_id')->find($primerDetalle->traspaso_id);
        if (! $pedido) {
            return response()->json(['ok' => false, 'message' => 'Pedido no encontrado.'], 422);
        }

        DB::transaction(function () use ($items, $pedido) {
            foreach ($items as $item) {
                $detalle = TraspasoDetalle::find($item['id'] ?? null);
                if (! $detalle || $detalle->traspaso_id !== $pedido->id) {
                    continue;
                }
                $cantidad = (float) ($item['cantidad_comprada'] ?? $detalle->cantidad_solicitada);
                $detalle->cantidad_comprada = $cantidad;
                $detalle->cantidad_recibida = $cantidad;
                $detalle->save();

                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->increment('stock', $cantidad);
                    if ($detalle->costo_unitario !== null) {
                        $producto->precio_compra = $detalle->costo_unitario;
                        $producto->save();
                    }
                }
            }
            $pedido->status = self::RECIBIDO;
            $pedido->save();
        });

        return response()->json(['ok' => true, 'message' => 'Compra finalizada.', 'pedido' => $pedido->fresh('detalles')]);
    }

    // --- Movimiento de stock (entrada de compra simplificada, legacy) ---

    /**
     * Legacy single-step stock entry from an earlier pass, kept for backward
     * compatibility (not wired to any real route — the real 2-phase pedido->arribo flow
     * is now implemented above via setProductosRequisicion/comprar + finalizarCompra).
     */
    public function registrarCompra(Request $request)
    {
        $producto = Producto::findOrFail($request->input('producto_id'));
        $cantidad = (float) $request->input('cantidad', 0);
        if ($cantidad <= 0) {
            return response()->json(['ok' => false, 'message' => 'La cantidad debe ser mayor a cero.'], 422);
        }
        $costoUnitario = $request->input('costo_unitario');

        $compra = Compra::create([
            'producto_id' => $producto->id,
            'proveedor_id' => $request->input('proveedor_id'),
            'cantidad' => $cantidad,
            'costo_unitario' => $costoUnitario,
            'users_id' => Auth::id(),
        ]);

        $producto->increment('stock', $cantidad);
        if ($costoUnitario !== null) {
            $producto->precio_compra = $costoUnitario;
            $producto->save();
        }

        return response()->json(['ok' => true, 'compra' => $compra, 'producto' => $producto->fresh()]);
    }
}
