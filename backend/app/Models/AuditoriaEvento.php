<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaEvento extends Model
{
    protected $table = 'auditoria_eventos';
    protected $guarded = [];

    public function conteos()
    {
        return $this->hasMany(AuditoriaConteo::class, 'auditoria_id');
    }
}
