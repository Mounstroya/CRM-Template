<?php

namespace App\Http\Controllers;

use App\Models\Deposito;
use Illuminate\Http\Request;

class DepositoController extends Controller
{
    public function consultaPorFecha(Request $request)
    {
        $fecha = $request->input('fecha');
        $depositos = Deposito::whereDate('fecha', $fecha)->get()->map(function ($d) {
            return [
                'id' => $d->id,
                'user_id' => $d->user_id,
                'monto' => $d->monto,
                'fecha' => $d->fecha,
                'comprobante' => $d->comprobante,
                'created_at' => $d->created_at,
                'updated_at' => $d->updated_at,
                'user' => [
                    'id' => $d->user_id,
                    'name' => $d->user_name,
                    'email' => $d->user_email,
                ],
            ];
        });

        return response()->json(['depositos' => $depositos]);
    }
}
