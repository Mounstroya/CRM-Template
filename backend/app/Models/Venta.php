<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $guarded = [];
    protected $casts = [
        'vendedores' => 'array',
        'departamentos' => 'array',
        'producto_vendido' => 'array',
    ];
}
