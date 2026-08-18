<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoWhatsapp extends Model
{
    protected $table = 'pedidos_whatsapp';
    protected $guarded = [];
    protected $casts = [
        'items' => 'array',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
