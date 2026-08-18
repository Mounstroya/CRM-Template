<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaConteo extends Model
{
    protected $table = 'auditoria_conteos';
    protected $guarded = [];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
