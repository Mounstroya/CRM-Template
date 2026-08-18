<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioCobro extends Model
{
    protected $table = 'servicio_cobros';
    protected $guarded = [];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}
