<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ClienteController extends Controller
{
    public function localGetClientes()
    {
        // nivel_numero is backfilled at seed time from the real text->number mapping
        // found in cartera-de-clientes.html's window.niveles (1=SUBDISTRIBUIDOR,
        // 2=PREFERENTE, 3=POR CAJA), which jspdv/pdv.js uses directly as
        // `precio_${nivel}` to pick the product price column.
        $clientes = Cliente::orderBy('nombre')->get()->map(function ($c) {
            $partes = explode(' ', trim($c->nombre ?? ''), 2);
            return [
                'id' => $c->id,
                'nombre' => $partes[0] ?? '',
                'apellido_p' => $partes[1] ?? '',
                'apellido_m' => '',
                'nivel' => $c->nivel_numero,
                'dias_credito' => $c->dias_credito,
                'limite_credito' => $c->limite_credito,
            ];
        });

        return response()->json(['clientes' => $clientes]);
    }

    public function tabla()
    {
        return view('partials.clientes_tabla', ['clientes' => Cliente::orderBy('nombre')->get()]);
    }

    public function getCliente(Request $request)
    {
        $cliente = Cliente::findOrFail($request->input('id'));
        return response()->json(['cliente' => $cliente]);
    }

    /**
     * Real route: POST /getDatosCredito (field cliente_id). Feeds punto-de-venta's
     * ventaCredito() — real response shape confirmed from js/app-pdv.js:
     * {limite_credito, dias_credito}.
     */
    public function getDatosCredito(Request $request)
    {
        $cliente = Cliente::find($request->input('cliente_id'));

        return response()->json([
            'limite_credito' => $cliente?->limite_credito,
            'dias_credito' => $cliente?->dias_credito,
        ]);
    }

    public function store(Request $request)
    {
        $cliente = Cliente::create([
            'nombre' => $request->input('nombre'),
            'correo' => $request->input('correo'),
            'telefono' => $request->input('telefono'),
            'nivel' => $request->input('nivel'),
            'status' => 'Activo',
        ]);
        return response()->json(['ok' => true, 'cliente' => $cliente]);
    }

    public function update(Request $request)
    {
        $cliente = Cliente::findOrFail($request->input('cliente_id'));
        $cliente->update($request->only(['nombre', 'correo', 'telefono', 'nivel']));
        return response()->json(['ok' => true, 'cliente' => $cliente]);
    }

    /**
     * Real route: DELETE /clientes-delete/{id}. Despite the name/confirm-dialog text
     * ("eliminar"), this deactivates rather than deletes — confirmed live: the button
     * it's wired to is labeled "Desactivar" and toggles status, doesn't remove the row.
     */
    public function deactivate(int $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->status = 'Inactivo';
        $cliente->save();

        return response()->json(['ok' => true, 'cliente' => $cliente]);
    }

    /** Real route: PUT /clientes-active/{id}. */
    public function activate(int $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->status = 'Activo';
        $cliente->save();

        return response()->json(['ok' => true, 'cliente' => $cliente]);
    }

    /**
     * Real route: GET /clientes/exportExcel. Implemented as a real CSV download
     * (opens correctly in Excel/Sheets) rather than a true .xlsx — no spreadsheet
     * library is in this project yet and a CSV serves the same real purpose
     * (downloadable client list) without adding a new dependency for this pass.
     */
    public function exportExcel()
    {
        $clientes = Cliente::orderBy('nombre')->get();

        return response()->streamDownload(function () use ($clientes) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Nombre', 'Correo', 'Teléfono', 'Nivel', 'Status']);
            foreach ($clientes as $c) {
                fputcsv($out, [$c->nombre, $c->correo, $c->telefono, $c->nivel, $c->status]);
            }
            fclose($out);
        }, 'clientes.csv', ['Content-Type' => 'text/csv']);
    }

    /**
     * Real route: POST /clientes/cargaMasiva (#formCargaMasiva, real rescued markup in
     * cartera_de_clientes.blade.php — plain multipart form submit, not AJAX, fields
     * archivo + omitir_encabezado). Column order matches exportExcel's own output
     * (Nombre, Correo, Teléfono, Nivel, Status) so a file round-tripped through
     * "Descargar Excel" -> edit -> "Carga Masiva" works. Real .xlsx and .csv both
     * accepted via PhpSpreadsheet (installed this pass, no longer --no-dev).
     */
    public function cargaMasiva(Request $request)
    {
        $request->validate(['archivo' => 'required|file']);

        $spreadsheet = IOFactory::load($request->file('archivo')->getRealPath());
        $filas = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        if ($request->boolean('omitir_encabezado', true) && count($filas) > 0) {
            array_shift($filas);
        }

        $creados = 0;
        foreach ($filas as $fila) {
            $nombre = trim((string) ($fila[0] ?? ''));
            if ($nombre === '') {
                continue;
            }
            Cliente::create([
                'nombre' => $nombre,
                'correo' => $fila[1] ?? null,
                'telefono' => $fila[2] ?? null,
                'nivel' => $fila[3] ?? null,
                'status' => $fila[4] ?? 'Activo',
            ]);
            $creados++;
        }

        return redirect('/cartera-de-clientes')->with('status', "{$creados} clientes importados con éxito.");
    }
}
