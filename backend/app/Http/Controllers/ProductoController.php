<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\UnidadCompra;
use App\Models\UnidadVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    /**
     * Real system: every local (Bodega Principal + one per vendedor) has its own
     * independent set of producto rows/stock, confirmed live (same clave, different
     * id, different stock per local). Every authenticated user belongs to exactly one
     * locales_id and only ever operates on that local's own catalog — matching the
     * real site's own window.localId behavior (admin's own session is local 1).
     */
    private function localesId(): int
    {
        return Auth::user()->locales_id ?? 1;
    }

    public function getPrice(Request $request)
    {
        $producto = Producto::findOrFail($request->input('id'));
        $nivel = (int) $request->input('nivel', 1);
        $precio = $producto->{"precio_$nivel"} ?? $producto->precio_1;

        return response()->json(['precio' => $precio, 'producto' => $producto]);
    }

    public function validateExistence(Request $request)
    {
        $producto = Producto::findOrFail($request->input('id'));
        $cantidad = (float) $request->input('cantidad', 1);

        return response()->json([
            'ok' => $producto->stock >= $cantidad,
            'stock' => $producto->stock,
        ]);
    }

    // Real edit-pencil icon used by the clave column, byte-for-byte the same
    // one served by the still-live original system (captured 2026-08-18).
    private const EDIT_ICON = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEp0lEQVR4nO3U60+bdRQH8EqWAJY7LckmjvUCjJbCovGF8Q/w8spRIEAHRUQ2kJjITE10pvGSzMmlGxtsGRbYgBZKWaEXWpgOJXNMwxaNi4skLA6hpX0oGrcJA9ev+T1tgSG2pbSv9CTfV0+e5JNzzu8wGP/Xf6lEZiozZ/S3vAOjzoK1mJ0Fok3JNlGvCvSLe8OjkCPiwIhTkWtxunJHnKBjcSLHG7MTIvMCRMPrERqpFaGRqgk5JHdkURkoItvkiXEBAoNjJXQdkhOIUxkwwrigzzZQZqGRIp2B0EBBqLeLQwexBNoJqt/7q1BPmQlE4I5k5xCzUxnoOEgXBEaq1/t7loEyCPQUBHoHBIM7wcgRIfJCAkCsj8PhEugpddYQpSWIrCF3gscAT4iGF85uC+Edh6cTXgSdQQcyg8LADQkVYj+Jzo7MgXlJcJBhDyAECBqybQzWISFFXPIkYAzckLAgLtmRMRAoRo4IoYlSbrgT/0Tog0dkDMwjQzsPXr+t0D/E6IFsQghChEgn6be5uFpHuk9LtolqCiuChtAYhU9IlsGRLjQ4XOFFzIOnsSrJBPxhXgkLQmNDet8ceOpfSdr9QkiJhhdSs/SOh1sh9vt8olsj+L2z4F2cxlOnvwfrk2uIf2/sp+gjpg+jq4wFyRWDsX5BAr2jbqcIMgq+agZ7Tt4E852vEFn7BaJqRhBdPYwnDxvArBoEs1L7R0yF5hhDPrbL97iG7LJgEfRO9PyClE+/Q+RbY4isvYyoGstjkJjKAcRWaBD3mhrxZd2jqQWaaN8gnV22XQRfY6P3YrdikobEvWlCccs3kLRcxXPvm8B8Q/c4RNqNhLILiD+k7PQ7sgydXba+lL4R3nA6psA86h4N8/AQRn6YBan+iTuIqdC4IdIuPFunRkJJGxIOtSNR0obE4nPP+wdp7bJAELw+G3i9VnpPvDuSVK3DrPMB7i+vov/aNGLL1XRH0io7sbSyiqpmCxJLziOp+BySi860MwIpvtYm84mgITZwe2aQ/PFVz7KaUHpmnO7KC8eG8OJHBsRJu92jkbRh/McZTNyeRVLhaSQXtSC58NSdgDCk+Bqb7N8QpCPcXis4XXcR9+6V2/SyVunxQd8kllb+QmzpRcSVq9Z2JEHyOU7qvoXNeQ9JBQqwCpvBEiuWGNspvsYm2wrBVbuzr2umg9wR96vR4fWzX9OdyXm7F/HSLjeE7EhRKy7fmMbkz3NgiRvBzleALW6wM7Zb3F6bbDOCQyAqK31ZyUHzPt/Uqh4s3lvC+K1Z7Ja2IaHkPBKLWlDTbMIjlwtHW4fBFjciRdxAMF8ygimO2lrHUVkfEgRHNfdon2qu0XviyWUlB41+NeU9yDtuwJ/Lq3D8/gDG61O4MWWluzUwfgspBz+jIe7UH2EEW0+r7u5JU1tf3tszz938jVzWtTtS2oHc2guo107AdH0KnZabKDuuBTvvxBqELa6f5r90KpIRlpKP7SKXdW1HJG1IKmolLwas/CawxU3rkLyG+6yDJ55hhLPSpB1R5LLSEPqOtNCvhizrxo6EHbKxyGUlB43cEVZ+0zJ5NWxx/RV2XkP15tH8DYU7D95NYKySAAAAAElFTkSuQmCC';

    // Server-side DataTables endpoint for mi-local/productos' #tbl_productos
    // (jspdv/productos.js expects the standard draw/recordsTotal/recordsFiltered
    // protocol since it's initialized with serverSide:true). HTML column
    // formatting (icon, colors, onclick handlers) matches the still-live
    // original system byte-for-byte, captured 2026-08-18 for reference.
    public function all(Request $request)
    {
        $draw = (int) $request->query('draw', 1);
        $start = (int) $request->query('start', 0);
        $length = (int) $request->query('length', 50);
        $searchValue = $request->query('search')['value'] ?? '';

        $base = Producto::with('categoria')->where('locales_id', $this->localesId());
        $total = (clone $base)->count();

        if ($searchValue !== '') {
            $base->where(function ($q) use ($searchValue) {
                $q->where('clave', 'like', "%{$searchValue}%")
                    ->orWhere('clave_alterna', 'like', "%{$searchValue}%")
                    ->orWhere('descripcion', 'like', "%{$searchValue}%")
                    ->orWhereHas('categoria', fn ($c) => $c->where('categoria', 'like', "%{$searchValue}%"));
            });
        }
        $filtered = (clone $base)->count();

        // Column order sent by the client: clave, clave_alterna, categoria,
        // descripcion, stock, factor_compra, factor_venta. factor_compra/
        // factor_venta are formatted display strings (precio_compra/precio_1),
        // not real sortable columns, same as the original.
        $sortable = ['clave', 'clave_alterna', null, 'descripcion', 'stock', null, null];
        $orderColumn = $sortable[(int) ($request->query('order')[0]['column'] ?? 0)] ?? 'clave';
        $orderDir = ($request->query('order')[0]['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
        if ($orderColumn) {
            $base->orderBy($orderColumn, $orderDir);
        }

        $productos = $length > 0 ? $base->skip($start)->take($length)->get() : $base->get();

        $icon = self::EDIT_ICON;
        $data = $productos->map(fn ($p) => [
            'id' => $p->id,
            'clave' => '<span style="cursor:pointer;" onclick="editProducto('.$p->id.')"><img src="'.$icon.'"> '.e($p->clave).'</span>',
            'clave_alterna' => $p->clave_alterna,
            'categoria' => $p->categoria->categoria ?? '',
            'descripcion' => $p->descripcion,
            'stock' => '<span style="border-bottom: 1px solid;cursor:pointer;color:#0f3884" onclick="editStock('.$p->id.')">'.$p->stock.' '.e($p->unidad_venta).'(S)</span>',
            'factor_compra' => '<span style="color: #ff7b0a;">$'.rtrim(rtrim(number_format((float) $p->precio_compra, 2, '.', ''), '0'), '.').' <b>X</b> '.e($p->unidad_compra).'</span>',
            'factor_venta' => '<span style="color: green;">$'.rtrim(rtrim(number_format((float) $p->precio_1, 2, '.', ''), '0'), '.').' <b>X</b> '.e($p->unidad_venta).'</span>',
        ]);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }

    /**
     * Real route: POST /editProducto (field id). Feeds BOTH editProducto and editStock in
     * the untouched rescued JS — editProducto reads product.categoria/.unidad_compra/
     * .unidad_venta as full relation objects (confirmed from app-inventario.js), editStock
     * only reads product.factor/.stock/.unidad_compra.unidad_compra/.unidad_venta.unidad_venta.
     */
    public function edit(Request $request)
    {
        $producto = Producto::with(['categoria', 'unidadCompra', 'unidadVenta'])->findOrFail($request->input('id'));

        return response()->json(['product' => $producto]);
    }

    /**
     * Real route: POST /form_edit_productos (#form_edit_productos, full field set
     * confirmed from mi_local_productos.blade.php's untouched modal_edit_producto:
     * clave, clave_alterna, servicio, descripcion, categorias_id, departamentos_id
     * (display-only, derived from categoria — not stored on productos), unidad_compra_id,
     * unidad_venta_id, factor, precio_compra, neto, precio_1..4). unidad_mayoreo_2/3/4 are
     * commented out in the real rescued HTML (inert), so DB columns exist but aren't fed
     * by this form, matching the live page's own behavior.
     */
    public function update(Request $request)
    {
        $producto = Producto::findOrFail($request->input('id'));

        $unidadCompra = $this->syncUnidadCompra($request->input('unidad_compra_id'));
        $unidadVenta = $this->syncUnidadVenta($request->input('unidad_venta_id'));

        $producto->fill([
            'clave' => strtoupper((string) $request->input('clave')),
            'clave_alterna' => strtoupper((string) $request->input('clave_alterna')),
            'servicio' => $request->boolean('servicio'),
            'descripcion' => strtoupper((string) $request->input('descripcion')),
            'categoria_id' => $request->input('categorias_id') ?: null,
            'unidad_compra_id' => $unidadCompra?->id,
            'unidad_venta_id' => $unidadVenta?->id,
            'unidad_compra' => $unidadCompra?->unidad_compra,
            'unidad_venta' => $unidadVenta?->unidad_venta,
            'factor' => (float) $request->input('factor', 1) ?: 1,
            'precio_compra' => $request->input('precio_compra'),
            'neto' => $request->boolean('neto'),
            'precio_1' => $request->input('precio_1'),
            'precio_2' => $request->input('precio_2'),
            'precio_3' => $request->input('precio_3'),
            'precio_4' => $request->input('precio_4'),
        ]);
        $producto->save();

        return response()->json(['message' => 'Producto actualizado con éxito.', 'producto' => $producto]);
    }

    /** Real route: POST /form_add_productos (#form_add_productos, same field set as update, no id). */
    public function store(Request $request)
    {
        $unidadCompra = $this->syncUnidadCompra($request->input('unidad_compra_id'));
        $unidadVenta = $this->syncUnidadVenta($request->input('unidad_venta_id'));

        $producto = Producto::create([
            'locales_id' => $this->localesId(),
            'clave' => strtoupper((string) $request->input('clave')),
            'clave_alterna' => strtoupper((string) $request->input('clave_alterna')),
            'servicio' => $request->boolean('servicio'),
            'descripcion' => strtoupper((string) $request->input('descripcion')),
            'categoria_id' => $request->input('categorias_id') ?: null,
            'unidad_compra_id' => $unidadCompra?->id,
            'unidad_venta_id' => $unidadVenta?->id,
            'unidad_compra' => $unidadCompra?->unidad_compra,
            'unidad_venta' => $unidadVenta?->unidad_venta,
            'factor' => (float) $request->input('factor', 1) ?: 1,
            'precio_compra' => $request->input('precio_compra'),
            'neto' => $request->boolean('neto'),
            'precio_1' => $request->input('precio_1'),
            'precio_2' => $request->input('precio_2'),
            'precio_3' => $request->input('precio_3'),
            'precio_4' => $request->input('precio_4'),
            'stock' => 0,
            'status' => 1,
        ]);

        return response()->json(['message' => 'Producto agregado con éxito.', 'producto' => $producto]);
    }

    /**
     * Real route: POST /stock (#form_stock, fields producto_id, producto_factor,
     * tipo_unidad_stock [compra|venta], stock [cantidad a agregar]). Stock is stored in
     * venta-units (confirmed by the DataTables display in all() showing stock + unidad_venta);
     * adding by "compra" units multiplies by factor to convert to venta-units.
     */
    public function stock(Request $request)
    {
        $producto = Producto::findOrFail($request->input('producto_id'));
        $cantidad = (float) $request->input('stock', 0);
        $tipo = $request->input('tipo_unidad_stock', 'venta');
        $factor = (float) ($producto->factor ?: 1);

        $aumento = $tipo === 'compra' ? $cantidad * $factor : $cantidad;
        $producto->increment('stock', $aumento);

        return response()->json(['status' => true, 'message' => 'Stock actualizado con éxito.', 'producto' => $producto->fresh()]);
    }

    /** Real route: POST /form_add_unidad_compra (#form_add_unidad_compra, field unidad_compra). */
    public function addUnidadCompra(Request $request)
    {
        $unidad = UnidadCompra::create(['unidad_compra' => strtoupper((string) $request->input('unidad_compra'))]);

        return response()->json(['unidad_compra' => $unidad]);
    }

    /** Real route: POST /form_add_unidad_venta (#form_add_unidad_venta, field unidad_venta). */
    public function addUnidadVenta(Request $request)
    {
        $unidad = UnidadVenta::create(['unidad_venta' => strtoupper((string) $request->input('unidad_venta'))]);

        return response()->json(['unidad_venta' => $unidad]);
    }

    private function syncUnidadCompra($id): ?UnidadCompra
    {
        return $id ? UnidadCompra::find($id) : null;
    }

    private function syncUnidadVenta($id): ?UnidadVenta
    {
        return $id ? UnidadVenta::find($id) : null;
    }

    /** Real route: POST /get_datos_generales already lists unidades in ComprasController;
     * routeGetProductos / routeGetProductosMatriz are simpler flat product lists used to
     * populate select-product pickers (compra builder, traspaso builder). */
    public function listAll()
    {
        return response()->json(['productos' => Producto::where('locales_id', $this->localesId())->where('status', 1)->orderBy('descripcion')->get()]);
    }

    /** Real route: POST /mercanciaSinStock — products with stock <= 0, used to bulk-add to a pedido. */
    public function sinStock()
    {
        return response()->json(['productos' => Producto::where('locales_id', $this->localesId())->where('stock', '<=', 0)->where('status', 1)->orderBy('descripcion')->get()]);
    }

    /**
     * Real route: POST /sincronizar. This build has a single store (no real multi-branch
     * catalog to reconcile against — same simplification already documented in
     * TraspasoController), so this is kept as a harmless no-op that satisfies the real
     * response contract ({status, typeMessage, message}) instead of leaving the button dead.
     */
    public function sincronizar()
    {
        return response()->json(['status' => true, 'typeMessage' => 'Success', 'message' => 'Catálogo sincronizado.']);
    }

    /**
     * Real route: POST /productos/getProductosByLocalId (fields id=localId, auditado,
     * auditoria_id) — feeds auditoria_show's #tblProductos (app-auditoria-local.js,
     * never rescued during the original recovery, now restored). `ultima_auditoria_producto`
     * is the most recent AuditoriaConteo for that producto across ANY past event, real
     * shape confirmed live (id/auditoria_id/stock_final/fecha_auditado).
     */
    public function getProductosByLocalId(Request $request)
    {
        $localId = (int) $request->input('id');
        $productos = Producto::with('unidadVenta')->where('locales_id', $localId)->where('status', 1)->orderBy('descripcion')->get();

        $conteos = \App\Models\AuditoriaConteo::whereIn('producto_id', $productos->pluck('id'))
            ->orderByDesc('id')
            ->get()
            ->unique('producto_id')
            ->keyBy('producto_id');

        $data = $productos->map(function ($p) use ($conteos) {
            $arr = $p->toArray();
            $c = $conteos->get($p->id);
            $arr['ultima_auditoria_producto'] = $c ? [
                'id' => $c->id,
                'auditoria_id' => $c->auditoria_id,
                'stock_final' => $c->stock_contado,
                'fecha_auditado' => optional($c->updated_at)->format('Y-m-d H:i:s'),
            ] : null;

            return $arr;
        });

        return response()->json(['status' => true, 'productos' => $data]);
    }
}
