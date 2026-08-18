<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Credito;
use App\Models\Producto;
use App\Models\Venta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function getVentas(Request $request)
    {
        $query = Venta::query();

        $inicio = $request->input('fecha_inicio');
        $fin = $request->input('fecha_fin') ?: $request->input('fecha_final');
        if ($inicio) {
            $query->whereDate('fecha_compra', '>=', $inicio);
        }
        if ($fin) {
            $query->whereDate('fecha_compra', '<=', $fin);
        }

        $ventas = $query->orderByDesc('fecha_compra')->get()->map(function ($v) {
            return [
                'id' => $v->id,
                'fecha_compra' => $v->fecha_compra,
                'local' => $v->vendedores,
                'total' => $v->total,
                'utilidad' => $v->utilidad,
                'no_venta' => $v->no_venta,
                'tipo_venta' => $v->tipo_venta,
                'status' => $v->status,
                'departamentos' => $v->departamentos,
                'productoVendido' => $v->producto_vendido,
            ];
        });

        return response()->json(['ventas' => $ventas, 'abonos' => []]);
    }

    /**
     * Registers a POS sale. utilidad = sum((precio_venta - precio_compra) * cantidad).
     * Credit sales (ventaTipo=1) use the client's real dias_credito (confirmed from
     * creditos.csv's embedded venta.cliente.dias_credito, per-client, not universal —
     * Phase 2's flat 15-day guess is gone). No interest: monto_total = sale total,
     * matching the real system (creditos.csv shows no markup between venta.total and
     * credito.monto_total). A client with dias_credito=0 has no real credit line and
     * is rejected, same as the real system would behave. Single default "local" (no
     * multi-branch selection in this build).
     */
    public function registroVenta(Request $request)
    {
        $productos = json_decode($request->input('productos', '[]'), true) ?: [];
        if (empty($productos)) {
            return response()->json(['ok' => false, 'message' => 'El carrito está vacío.'], 422);
        }

        $clienteId = (int) $request->input('cliente_id', 0);
        $ventaTipo = (int) $request->input('ventaTipo', 0);
        $cliente = $clienteId ? Cliente::find($clienteId) : null;

        if ($ventaTipo === 1 && (! $cliente || (int) $cliente->dias_credito <= 0)) {
            return response()->json(['ok' => false, 'message' => 'Este cliente no tiene línea de crédito activa.'], 422);
        }

        $venta = $this->crearVenta($productos, $ventaTipo, $cliente, Auth::user()->name);

        return response()->json(['ok' => true, 'venta' => $venta]);
    }

    /**
     * Core sale-registration logic shared by the POS endpoint and
     * WhatsappController::confirmarPedido (Fase 4 — confirming a pedido_whatsapp
     * reuses this exact path instead of duplicating it, per the owner's request).
     * Throws (via abort()) on insufficient stock — same behavior as the HTTP endpoint.
     */
    public function crearVenta(array $productos, int $ventaTipo, ?Cliente $cliente, string $vendedorNombre): Venta
    {
        return DB::transaction(function () use ($productos, $ventaTipo, $cliente, $vendedorNombre) {
            $total = 0;
            $utilidad = 0;
            $productoVendido = [];

            foreach ($productos as $item) {
                $producto = Producto::find($item['id']);
                if (! $producto) {
                    continue;
                }
                $cantidad = (float) ($item['cantidad'] ?? 1);
                $precioVenta = (float) ($item['precio_venta'] ?? $producto->precio_1);
                $esServicio = (int) ($item['servicio'] ?? 0) === 1;

                if (! $esServicio && $producto->stock < $cantidad) {
                    abort(422, "Sin existencia suficiente de {$producto->descripcion} (stock: {$producto->stock}).");
                }

                $total += $precioVenta * $cantidad;
                $utilidad += ($precioVenta - (float) ($producto->precio_compra ?? 0)) * $cantidad;

                if (! $esServicio) {
                    $producto->decrement('stock', $cantidad);
                }

                $productoVendido[] = [
                    'id' => $producto->id,
                    'nombre' => $producto->descripcion,
                    'cantidad' => number_format($cantidad, 2, '.', ''),
                ];
            }

            $noVenta = (int) (Venta::max('no_venta') ?? 0) + 1;

            $venta = Venta::create([
                'fecha_compra' => now(),
                'vendedores' => [$vendedorNombre],
                'cliente_id' => $cliente?->id,
                'total' => $total,
                'utilidad' => $utilidad,
                'no_venta' => $noVenta,
                'tipo_venta' => $ventaTipo,
                // Real encoding confirmed from the seeded historical data itself
                // (5,626 rows): 0=Cancelada, 2=Contado, 3=Crédito (status=3 count
                // matched the 58 real créditos exactly). Previously hardcoded to 1,
                // which doesn't exist as a real status and could hide/misfilter sales
                // in the rescued JS (app-ventas.js checks `3 == status` for Vendedor
                // visibility) — fixed.
                'status' => $ventaTipo === 1 ? 3 : 2,
                'departamentos' => [],
                'producto_vendido' => $productoVendido,
            ]);

            if ($ventaTipo === 1) {
                $plazo = (int) $cliente->dias_credito;
                Credito::create([
                    'venta_id' => $venta->id,
                    'fecha_venta' => $venta->fecha_compra,
                    'plazo_pago' => $plazo,
                    'fecha_vencimiento' => now()->addDays($plazo)->toDateString(),
                    'monto_total' => $total,
                    'monto_pagado' => 0,
                    'estado_pago' => 0,
                    'cliente_nombre' => $cliente?->nombre,
                    'no_venta' => $noVenta,
                ]);
            }

            return $venta;
        });
    }

    /**
     * Real route: POST /reporteExcel (fields fecha_inicio/fecha_fin/asesor, same
     * filters as getVentas). Same CSV-not-true-xlsx tradeoff as
     * ClienteController::exportExcel, documented there.
     */
    public function reporteExcel(Request $request)
    {
        $query = Venta::query();
        if ($inicio = $request->input('fecha_inicio')) {
            $query->whereDate('fecha_compra', '>=', $inicio);
        }
        if ($fin = $request->input('fecha_fin')) {
            $query->whereDate('fecha_compra', '<=', $fin);
        }
        $ventas = $query->orderByDesc('fecha_compra')->get();

        return response()->streamDownload(function () use ($ventas) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['No. Venta', 'Fecha', 'Total', 'Utilidad', 'Tipo', 'Status']);
            foreach ($ventas as $v) {
                fputcsv($out, [$v->no_venta, $v->fecha_compra, $v->total, $v->utilidad, $v->tipo_venta, $v->status]);
            }
            fclose($out);
        }, 'historico-ventas.csv', ['Content-Type' => 'text/csv']);
    }

    /**
     * Real route: POST /verNota (field venta_id), feeds historico's receipt modal
     * (#tbl_prod_nota: #, PRODUCTO, PRECIO, CANT., TOTAL per line). Real limitation:
     * producto_vendido only ever stored {id, nombre, cantidad} — no per-line historical
     * price — so precio/total per line use the product's current precio_1 as a
     * best-effort approximation for historical sales, documented, not exact.
     */
    public function verNota(Request $request)
    {
        $venta = Venta::findOrFail($request->input('venta_id'));
        $items = $this->notaItems($venta);

        return response()->json([
            'venta' => $items,
            'no_venta' => $venta->no_venta,
            'fecha_compra' => $venta->fecha_compra,
            'total' => $venta->total,
        ]);
    }

    private function notaItems(Venta $venta)
    {
        return collect($venta->producto_vendido ?? [])->map(function ($item) {
            $producto = Producto::find($item['id']);
            $precio = $producto?->precio_1 ?? 0;
            $cantidad = (float) $item['cantidad'];

            return [
                'producto' => $item['nombre'],
                'precio' => $precio,
                'cantidad' => $cantidad,
                'total' => $precio * $cantidad,
            ];
        })->all();
    }

    /**
     * Real route: POST /descargar-nota-compra (field ventas_id), fed by historico's
     * #form_imp_nota_venta (window.btn_imp_nota — a real, non-AJAX form submit so the
     * browser downloads the file directly). Reuses the same item-building logic as
     * verNota() so the PDF and the "Vista rápida" modal always agree.
     */
    public function descargarNotaPdf(Request $request)
    {
        $venta = Venta::findOrFail($request->input('ventas_id'));
        $items = $this->notaItems($venta);

        $pdf = Pdf::loadView('reportes.nota_venta_pdf', [
            'venta' => $venta,
            'items' => $items,
        ]);

        return $pdf->download('nota-A'.$venta->no_venta.'.pdf');
    }

    /**
     * Real route: POST /ventas.cancelar (fields venta_id, forma_pago, motivo per
     * #formCancelarVenta). Restocks every line item and marks the sale cancelled
     * (status=0, matching the real encoding — see crearVenta). If the sale was a
     * credit sale, the linked Credito is cancelled too (estado_pago has no real
     * "cancelado" code in the rescued data, so it's zeroed out and monto_total set to
     * 0 rather than inventing a new status code).
     */
    public function cancelar(Request $request)
    {
        $venta = Venta::findOrFail($request->input('venta_id'));
        if ($venta->status === 0) {
            return response()->json(['status' => false, 'message' => 'Esta venta ya está cancelada.'], 422);
        }

        DB::transaction(function () use ($venta, $request) {
            foreach ($venta->producto_vendido ?? [] as $item) {
                Producto::where('id', $item['id'])->increment('stock', (float) $item['cantidad']);
            }

            $venta->status = 0;
            $venta->motivo_cancelacion = $request->input('motivo', $request->input('forma_pago'));
            $venta->fecha_cancelacion = now();
            $venta->usuario_cancelacion = Auth::user()->name;
            $venta->save();

            Credito::where('venta_id', $venta->id)->update(['monto_total' => 0, 'monto_pagado' => 0]);
        });

        return response()->json(['status' => true, 'message' => 'La venta ha sido cancelada y se han retornado los productos al almacén.']);
    }

    /**
     * Real route: POST /ventas.cancelarProducto (fields venta_id, producto_id, cantidad
     * per historico's #modal_cancelacion_producto, real JS confirmed from
     * js/app-ventas.js). Cancels only `cantidad` units of one line item — restocks that
     * quantity, reduces total/utilidad proportionally, removes the line if it reaches
     * zero, and cancels the whole sale (existing cancelar() path) once every line is gone.
     * Credit sales get their monto_total reduced by the same proportion.
     */
    public function cancelarProducto(Request $request)
    {
        $venta = Venta::findOrFail($request->input('venta_id'));
        if ($venta->status === 0) {
            return response()->json(['status' => false, 'message' => 'Esta venta ya está cancelada.'], 422);
        }

        $productoId = (int) $request->input('producto_id');
        $cantidadCancelar = (float) $request->input('cantidad', 0);
        $items = $venta->producto_vendido ?? [];
        $idx = null;
        foreach ($items as $i => $item) {
            if ((int) $item['id'] === $productoId) {
                $idx = $i;
                break;
            }
        }
        if ($idx === null) {
            return response()->json(['status' => false, 'message' => 'El producto no pertenece a esta venta.'], 422);
        }

        $cantidadLinea = (float) $items[$idx]['cantidad'];
        if ($cantidadCancelar < 1 || $cantidadCancelar > $cantidadLinea) {
            return response()->json(['status' => false, 'message' => 'Cantidad inválida.'], 422);
        }

        DB::transaction(function () use ($venta, $items, $idx, $productoId, $cantidadCancelar, $cantidadLinea) {
            $producto = Producto::find($productoId);
            $precioVenta = $producto->precio_1 ?? 0;
            $montoLinea = $cantidadCancelar * $precioVenta;
            $utilidadLinea = $cantidadCancelar * ($precioVenta - (float) ($producto->precio_compra ?? 0));

            Producto::where('id', $productoId)->increment('stock', $cantidadCancelar);

            if ($cantidadCancelar >= $cantidadLinea) {
                array_splice($items, $idx, 1);
            } else {
                $items[$idx]['cantidad'] = number_format($cantidadLinea - $cantidadCancelar, 2, '.', '');
            }

            $venta->producto_vendido = $items;
            $venta->total = max(0, (float) $venta->total - $montoLinea);
            $venta->utilidad = (float) $venta->utilidad - $utilidadLinea;
            $venta->save();

            $credito = Credito::where('venta_id', $venta->id)->first();
            if ($credito) {
                $credito->monto_total = max(0, (float) $credito->monto_total - $montoLinea);
                $credito->save();
            }

            if (empty($items)) {
                $venta->status = 0;
                $venta->motivo_cancelacion = 'Cancelación de todos los productos (parcial acumulada)';
                $venta->fecha_cancelacion = now();
                $venta->usuario_cancelacion = Auth::user()->name;
                $venta->save();
                if ($credito) {
                    $credito->monto_total = 0;
                    $credito->monto_pagado = 0;
                    $credito->save();
                }
            }
        });

        return response()->json(['status' => true, 'message' => 'Producto cancelado y devuelto al almacén.', 'venta_id' => $venta->id]);
    }

    /**
     * Real route: POST /ventas.verCancelacion (field venta_id). Real response shape
     * confirmed from js/app-ventas.js's window.verNotaCancelada: {status, venta:
     * {fecha_cancelacion, usuario_cancelacion:{name}, motivo_cancelacion}} — the real JS
     * reads usuario_cancelacion as an object with a .name property.
     */
    public function verCancelacion(Request $request)
    {
        $venta = Venta::findOrFail($request->input('venta_id'));

        return response()->json([
            'status' => true,
            'venta' => [
                'fecha_cancelacion' => $venta->fecha_cancelacion,
                'motivo_cancelacion' => $venta->motivo_cancelacion,
                'usuario_cancelacion' => $venta->usuario_cancelacion ? ['name' => $venta->usuario_cancelacion] : null,
            ],
        ]);
    }
}
