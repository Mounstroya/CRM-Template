<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraspasoDetalle extends Model
{
    protected $table = 'traspaso_detalles';
    protected $guarded = [];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
