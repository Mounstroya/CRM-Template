<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $guarded = [];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function unidadCompra()
    {
        return $this->belongsTo(UnidadCompra::class, 'unidad_compra_id');
    }

    public function unidadVenta()
    {
        return $this->belongsTo(UnidadVenta::class, 'unidad_venta_id');
    }
}
