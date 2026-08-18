<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaConteo;
use App\Models\Producto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Real routes recovered from the live site's app-auditoria.js (untouched, still
 * loaded as-is): the /auditoria page's #tblLocales is a client-side DataTable fed by
 * these AJAX endpoints, not server-rendered — restored that mechanism here instead of
 * the Fase 3 server-side Blade loop, since it drives the real 3-state GENERAR/
 * CONTINUAR button logic this JS already implements correctly.
 */
class AuditoriaController extends Controller
{
    public function getLocalesAuditoria()
    {
        $sucursales = Auditoria::all()->map(function ($a) {
            return [
                'id' => $a->id,
                'parent_id' => $a->parent_id,
                'nombre' => $a->nombre,
                'status' => $a->status,
                'ultima_auditoria' => $a->ultima_auditoria_id ? [
                    'id' => $a->ultima_auditoria_id,
                    'local_id' => $a->id,
                    'fecha_inicio' => $a->ultima_auditoria_inicio,
                    'fecha_fin' => $a->ultima_auditoria_fin,
                    'no_auditoria' => $a->ultima_auditoria_no,
                ] : null,
            ];
        });

        return response()->json(['sucursales' => $sucursales]);
    }

    /**
     * Real route: POST /autidoria/nueva-auditoria (field: id = sucursal id). Starts a
     * new in-progress audit (fecha_fin null → real JS shows "CONTINUAR" for it, which
     * links to AuditoriaController::show below — the real checklist/counting process).
     * Assumption: no_auditoria increments from the highest seen so far.
     */
    public function nuevaAuditoria(Request $request)
    {
        $sucursal = Auditoria::findOrFail($request->input('id'));

        $maxNo = (int) Auditoria::max('ultima_auditoria_no');
        $sucursal->ultima_auditoria_id = (int) (Auditoria::max('ultima_auditoria_id') ?? 0) + 1;
        $sucursal->ultima_auditoria_no = (string) ($maxNo + 1);
        $sucursal->ultima_auditoria_inicio = now();
        $sucursal->ultima_auditoria_fin = null;
        $sucursal->save();

        return response()->json(['ok' => true, 'sucursal' => $sucursal]);
    }

    /** Real route: POST /auditoria/fechas-auditoria-local (field: id = sucursal id). */
    public function fechasAuditoriaLocal(Request $request)
    {
        $sucursal = Auditoria::findOrFail($request->input('id'));
        $fechas = $sucursal->ultima_auditoria_inicio
            ? [['id' => $sucursal->ultima_auditoria_id, 'fecha' => optional($sucursal->ultima_auditoria_inicio)->format('Y-m-d')]]
            : [];

        return response()->json($fechas);
    }

    /**
     * Own design, documented in the 2026_08_18_000004 migration: the real
     * auditoria/{id}/show "toma de auditoría" page could not be checked against the still-
     * live original this pass (needs the real production login, unavailable in this
     * session). This implements the plainly-implied need — count every active product's
     * real stock and compare it against the system's stock — using this project's own
     * conventions.
     *
     * Real evidence (js/app-auditoria.js's #tblLocales DataTable column render, confirmed
     * via a Playwright click-through that 404'd on the first attempt): the GENERAR/
     * CONTINUAR/VER ÚLTIMA links use href="auditoria/{ultima_auditoria.id}/show" — the
     * `data:"ultima_auditoria"` column's own nested `.id`, i.e. our ultima_auditoria_id
     * counter — NOT the sucursal's own primary id. $id here must resolve against
     * ultima_auditoria_id, not Auditoria::find($id).
     */
    public function show($id)
    {
        $sucursal = Auditoria::where('ultima_auditoria_id', $id)->firstOrFail();
        $productos = Producto::where('status', 1)->orderBy('descripcion')->get();

        $conteos = AuditoriaConteo::where('auditoria_id', $sucursal->id)
            ->where('no_auditoria', $sucursal->ultima_auditoria_no)
            ->get()
            ->keyBy('producto_id');

        return view('auditoria_show', [
            'sucursal' => $sucursal,
            'productos' => $productos,
            'conteos' => $conteos,
        ]);
    }

    /** Own design: saves/updates one product's counted stock for the in-progress audit. $id = ultima_auditoria_id, see show() above. */
    public function guardarConteo(Request $request, $id)
    {
        $sucursal = Auditoria::where('ultima_auditoria_id', $id)->firstOrFail();
        $producto = Producto::findOrFail($request->input('producto_id'));
        $stockContado = (int) $request->input('stock_contado');

        $conteo = AuditoriaConteo::updateOrCreate(
            [
                'auditoria_id' => $sucursal->id,
                'no_auditoria' => $sucursal->ultima_auditoria_no,
                'producto_id' => $producto->id,
            ],
            [
                'stock_sistema' => $producto->stock,
                'stock_contado' => $stockContado,
                'diferencia' => $stockContado - (int) $producto->stock,
                'users_id' => Auth::id(),
            ]
        );

        return response()->json(['ok' => true, 'conteo' => $conteo]);
    }

    /** Own design: closes the in-progress audit (sets ultima_auditoria_fin), same GENERAR/CONTINUAR state machine the real /auditoria listing already reads. $id = ultima_auditoria_id, see show() above. */
    public function finalizar(Request $request, $id)
    {
        $sucursal = Auditoria::where('ultima_auditoria_id', $id)->firstOrFail();
        $sucursal->ultima_auditoria_fin = now();
        $sucursal->save();

        $diferencias = AuditoriaConteo::where('auditoria_id', $sucursal->id)
            ->where('no_auditoria', $sucursal->ultima_auditoria_no)
            ->whereNotNull('diferencia')
            ->where('diferencia', '!=', 0)
            ->count();

        return response()->json(['ok' => true, 'message' => 'Auditoría finalizada.', 'diferencias' => $diferencias, 'sucursal' => $sucursal]);
    }

    /**
     * Real route: POST /auditoria/reporte-local-excel (#btnExportarLocalExcel, field id =
     * local id, plain form POST/download per app-auditoria.js). CSV, same documented
     * Excel-as-CSV convention as ClienteController::exportExcel.
     */
    public function reporteLocalExcel(Request $request)
    {
        $sucursal = Auditoria::findOrFail($request->input('id'));
        $conteos = AuditoriaConteo::with('producto')
            ->where('auditoria_id', $sucursal->id)
            ->where('no_auditoria', $sucursal->ultima_auditoria_no)
            ->get();

        return response()->streamDownload(function () use ($conteos) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Clave', 'Producto', 'Stock Sistema', 'Stock Contado', 'Diferencia']);
            foreach ($conteos as $c) {
                fputcsv($out, [$c->producto?->clave, $c->producto?->descripcion, $c->stock_sistema, $c->stock_contado, $c->diferencia]);
            }
            fclose($out);
        }, 'auditoria-'.$sucursal->id.'.csv', ['Content-Type' => 'text/csv']);
    }

    /**
     * Real route: POST /auditoria/reporte-local-pdf (#btnExportarLocalPDF, field id =
     * local id). Real PDF via barryvdh/laravel-dompdf (installed this pass).
     */
    public function reporteLocalPdf(Request $request)
    {
        $sucursal = Auditoria::findOrFail($request->input('id'));
        $conteos = AuditoriaConteo::with('producto')
            ->where('auditoria_id', $sucursal->id)
            ->where('no_auditoria', $sucursal->ultima_auditoria_no)
            ->get();

        $pdf = Pdf::loadView('reportes.auditoria_pdf', [
            'sucursal' => $sucursal,
            'conteos' => $conteos,
        ]);

        return $pdf->download('auditoria-'.$sucursal->id.'.pdf');
    }
}
