<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\AuditoriaConteo;
use App\Models\AuditoriaEvento;
use App\Models\Producto;
use App\Models\Traspaso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Real routes recovered from the live site's app-auditoria.js (untouched, still
 * loaded as-is): the /auditoria page's #tblLocales is a client-side DataTable fed by
 * these AJAX endpoints, not server-rendered.
 *
 * `Auditoria` = locales/sucursales themselves (unchanged meaning). `AuditoriaEvento`
 * = one real audit EVENT for a local — the real site tracks many of these per local
 * over time (confirmed live: Bodega Principal alone has 10 historical audits), so
 * this replaced an earlier "own design" pass that only ever tracked one
 * ultima_auditoria_* set of columns directly on `Auditoria`.
 */
class AuditoriaController extends Controller
{
    public function getLocalesAuditoria()
    {
        $ultimas = AuditoriaEvento::orderByDesc('fecha_inicio')->get()->groupBy('local_id');

        $sucursales = Auditoria::all()->map(function ($a) use ($ultimas) {
            $ultima = $ultimas->get($a->id)?->first();

            return [
                'id' => $a->id,
                'parent_id' => $a->parent_id,
                'nombre' => $a->nombre,
                'status' => $a->status,
                'ultima_auditoria' => $ultima ? [
                    'id' => $ultima->id,
                    'local_id' => $a->id,
                    'fecha_inicio' => $ultima->fecha_inicio,
                    'fecha_fin' => $ultima->fecha_fin,
                    'no_auditoria' => $ultima->no_auditoria,
                ] : null,
            ];
        });

        return response()->json(['sucursales' => $sucursales]);
    }

    /** Real route: POST /autidoria/nueva-auditoria (field: id = sucursal id). Starts a new AuditoriaEvento. */
    public function nuevaAuditoria(Request $request)
    {
        $sucursal = Auditoria::findOrFail($request->input('id'));

        $maxNo = (int) AuditoriaEvento::max('no_auditoria');
        $evento = AuditoriaEvento::create([
            'local_id' => $sucursal->id,
            'no_auditoria' => (string) ($maxNo + 1),
            'auditor_nombre' => Auth::user()->name,
            'fecha_inicio' => now(),
            'fecha_fin' => null,
        ]);

        return response()->json(['ok' => true, 'sucursal' => $sucursal, 'evento' => $evento]);
    }

    /** Real route: POST /auditoria/fechas-auditoria-local (field: id = sucursal id). Full real history, not just the latest. */
    public function fechasAuditoriaLocal(Request $request)
    {
        $eventos = AuditoriaEvento::where('local_id', $request->input('id'))
            ->orderByDesc('fecha_inicio')
            ->get(['id', 'fecha_inicio', 'fecha_fin']);

        return response()->json(['auditorias' => $eventos]);
    }

    /** $id here is the AuditoriaEvento's own id (see class docblock). */
    public function show($id)
    {
        $evento = AuditoriaEvento::findOrFail($id);
        $sucursal = Auditoria::findOrFail($evento->local_id);
        $productos = Producto::where('locales_id', $evento->local_id)->where('status', 1)->orderBy('descripcion')->get();

        $conteos = AuditoriaConteo::where('auditoria_id', $evento->id)->get()->keyBy('producto_id');

        return view('auditoria_show', [
            'sucursal' => $sucursal,
            'evento' => $evento,
            'productos' => $productos,
            'conteos' => $conteos,
        ]);
    }

    /** Saves/updates one product's counted stock for the in-progress AuditoriaEvento. */
    public function guardarConteo(Request $request, $id)
    {
        $evento = AuditoriaEvento::findOrFail($id);
        $producto = Producto::findOrFail($request->input('producto_id'));
        $stockContado = (int) $request->input('stock_contado');

        $conteo = AuditoriaConteo::updateOrCreate(
            ['auditoria_id' => $evento->id, 'producto_id' => $producto->id],
            [
                'no_auditoria' => $evento->no_auditoria,
                'clave' => $producto->clave,
                'stock_sistema' => $producto->stock,
                'stock_contado' => $stockContado,
                'diferencia' => $stockContado - (int) $producto->stock,
                'users_id' => Auth::id(),
            ]
        );

        return response()->json(['ok' => true, 'conteo' => $conteo]);
    }

    /** Closes the in-progress AuditoriaEvento. */
    public function finalizar(Request $request, $id)
    {
        $evento = AuditoriaEvento::findOrFail($id);
        $evento->fecha_fin = now();
        $evento->save();

        $diferencias = AuditoriaConteo::where('auditoria_id', $evento->id)
            ->whereNotNull('diferencia')
            ->where('diferencia', '!=', 0)
            ->count();

        return response()->json(['ok' => true, 'message' => 'Auditoría finalizada.', 'diferencias' => $diferencias, 'sucursal' => Auditoria::find($evento->local_id)]);
    }

    /**
     * Real route: POST /auditoria/reporte-local-excel (#btnExportarLocalExcel, field id =
     * local id). "Exportar Todas las Auditorías del Local" — spans every AuditoriaEvento
     * for that local, not just one, per the real sidebar copy ("agrupadas por producto
     * para detectar anomalías").
     */
    public function reporteLocalExcel(Request $request)
    {
        $sucursal = Auditoria::findOrFail($request->input('id'));
        $eventoIds = AuditoriaEvento::where('local_id', $sucursal->id)->pluck('id');
        $conteos = AuditoriaConteo::with('producto')->whereIn('auditoria_id', $eventoIds)->get();

        return response()->streamDownload(function () use ($conteos) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Clave', 'Producto', 'Stock Sistema', 'Stock Contado', 'Diferencia']);
            foreach ($conteos as $c) {
                fputcsv($out, [$c->clave ?? $c->producto?->clave, $c->producto?->descripcion, $c->stock_sistema, $c->stock_contado, $c->diferencia]);
            }
            fclose($out);
        }, 'auditoria-'.$sucursal->id.'.csv', ['Content-Type' => 'text/csv']);
    }

    /** Real route: POST /auditoria/reporte-local-pdf (#btnExportarLocalPDF, field id = local id). */
    public function reporteLocalPdf(Request $request)
    {
        $sucursal = Auditoria::findOrFail($request->input('id'));
        $eventoIds = AuditoriaEvento::where('local_id', $sucursal->id)->pluck('id');
        $conteos = AuditoriaConteo::with('producto')->whereIn('auditoria_id', $eventoIds)->get();

        $pdf = Pdf::loadView('reportes.auditoria_pdf', [
            'sucursal' => $sucursal,
            'conteos' => $conteos,
        ]);

        return $pdf->download('auditoria-'.$sucursal->id.'.pdf');
    }

    /**
     * Real route: POST /auditoria/reporte-auditoria (field id = AuditoriaEvento id).
     * Per-event Excel summary — real column set confirmed live (byte-for-byte header
     * match): LOCAL/AUDITOR/FECHA/CLAVE/PRODUCTO/STOCK INICIAL/ENTRADAS/SALIDAS/
     * CALCULADO/CONTADO/DIFERENCIA/COSTO VENTA/COMENTARIO.
     */
    public function reporteAuditoria(Request $request)
    {
        $evento = AuditoriaEvento::findOrFail($request->input('id'));
        $sucursal = Auditoria::find($evento->local_id);
        $conteos = AuditoriaConteo::with('producto')->where('auditoria_id', $evento->id)->get();

        return response()->streamDownload(function () use ($evento, $sucursal, $conteos) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['LOCAL', 'AUDITOR', 'FECHA', 'CLAVE', 'PRODUCTO', 'STOCK INICIAL', 'ENTRADAS', 'SALIDAS', 'CALCULADO', 'CONTADO', 'DIFERENCIA', 'COSTO VENTA', 'COMENTARIO']);
            foreach ($conteos as $c) {
                fputcsv($out, [
                    $sucursal?->nombre, $evento->auditor_nombre, $evento->fecha_inicio,
                    $c->clave ?? $c->producto?->clave, $c->producto?->descripcion,
                    $c->stock_sistema, $c->entradas, $c->salidas, $c->calculado, $c->stock_contado,
                    $c->diferencia, $c->costo_venta, $c->comentario,
                ]);
            }
            fclose($out);
        }, 'reporte-auditoria-'.$evento->id.'.csv', ['Content-Type' => 'text/csv']);
    }

    /**
     * Real route: POST /auditoria/reporte-detallado (field id = AuditoriaEvento id,
     * "Detalle de todas las entradas y salidas"). Real column set confirmed live:
     * PRODUCTO/TIPO MOVIMIENTO/FECHA/DOCUMENTO/CANTIDAD/PRECIO UNITARIO/TOTAL/
     * ORIGEN-DESTINO/NOTAS. Documented simplification: this build's traspaso_detalles
     * don't carry a precio_unitario, so that column is left blank rather than guessed.
     */
    public function reporteDetallado(Request $request)
    {
        $evento = AuditoriaEvento::findOrFail($request->input('id'));
        $movimientos = Traspaso::with('detalles.producto')
            ->where(function ($q) use ($evento) {
                $q->where('sucursal_origen_id', $evento->local_id)->orWhere('sucursal_destino_id', $evento->local_id);
            })
            ->get();

        return response()->streamDownload(function () use ($movimientos, $evento) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['PRODUCTO', 'TIPO MOVIMIENTO', 'FECHA', 'DOCUMENTO', 'CANTIDAD', 'PRECIO UNITARIO', 'TOTAL', 'ORIGEN/DESTINO', 'NOTAS']);
            foreach ($movimientos as $t) {
                $tipo = $t->sucursal_origen_id == $evento->local_id ? 'SALIDA' : 'ENTRADA';
                $otro = $t->sucursal_origen_id == $evento->local_id ? $t->sucursal_destino_id : $t->sucursal_origen_id;
                foreach ($t->detalles as $d) {
                    fputcsv($out, [
                        $d->producto?->descripcion, $tipo, $t->created_at, $t->no_requisicion,
                        $d->cantidad_recibida ?? $d->cantidad_enviada ?? $d->cantidad_solicitada, null, null, $otro, '',
                    ]);
                }
            }
            fclose($out);
        }, 'reporte-detallado-'.$evento->id.'.csv', ['Content-Type' => 'text/csv']);
    }

    /** Real route: POST /auditoria/reporte/pdf (field id = AuditoriaEvento id). */
    public function reportePdf(Request $request)
    {
        $evento = AuditoriaEvento::findOrFail($request->input('id'));
        $sucursal = Auditoria::find($evento->local_id);
        $conteos = AuditoriaConteo::with('producto')->where('auditoria_id', $evento->id)->get();

        $pdf = Pdf::loadView('reportes.auditoria_pdf', [
            'sucursal' => $sucursal,
            'conteos' => $conteos,
        ]);

        return $pdf->download('reporte-auditoria-'.$evento->id.'.pdf');
    }
}
