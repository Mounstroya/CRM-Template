<?php

namespace App\Http\Controllers;

use App\Models\Credito;
use Illuminate\Http\Request;

class CreditoController extends Controller
{
    public function getCreditos()
    {
        $creditos = Credito::orderByDesc('fecha_venta')->get()->map(function ($c) {
            $partes = explode(' ', trim($c->cliente_nombre ?? ''), 3);
            return [
                'id' => $c->id,
                'fecha_vencimiento' => $c->fecha_vencimiento,
                'monto_total' => $c->monto_total,
                'monto_pagado' => $c->monto_pagado,
                'estado_pago' => $c->estado_pago,
                'venta' => [
                    'no_venta' => $c->no_venta,
                    'fecha_compra' => $c->fecha_venta,
                    'cliente' => [
                        'nombre' => $partes[0] ?? '',
                        'apellido_p' => $partes[1] ?? '',
                        'apellido_m' => $partes[2] ?? '',
                    ],
                ],
            ];
        });

        return response()->json(['creditos' => $creditos]);
    }

    public function tablaAbonos()
    {
        return response()->json(['abonos' => []]);
    }

    public function abonar(Request $request)
    {
        $credito = Credito::findOrFail($request->input('credito_id'));

        $abono = (float) $request->input('efectivo', 0)
            + (float) $request->input('tarjeta', 0)
            + (float) $request->input('transferencia', 0);

        if ($abono <= 0) {
            return response()->json(['ok' => false, 'message' => 'El monto abonado debe ser mayor a cero.'], 422);
        }

        $nuevoPagado = min((float) $credito->monto_total, (float) $credito->monto_pagado + $abono);
        $credito->monto_pagado = $nuevoPagado;
        $credito->estado_pago = $nuevoPagado >= (float) $credito->monto_total ? 1 : 0;
        $credito->save();

        return response()->json([
            'ok' => true,
            'monto_pagado' => $credito->monto_pagado,
            'estado_pago' => $credito->estado_pago,
        ]);
    }
}
