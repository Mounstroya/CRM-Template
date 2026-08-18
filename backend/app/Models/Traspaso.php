<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traspaso extends Model
{
    protected $table = 'traspasos';
    protected $guarded = [];

    public function detalles()
    {
        return $this->hasMany(TraspasoDetalle::class, 'traspaso_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
}
