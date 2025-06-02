<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table='orders';
    protected $fillable= [
        'id_user',
        'fecha',
        'precio_envio',
        'precio_total',
        'estatus',
        'id_address',
    ];

    /** Relación muchos a muchos con Items */
    public function items()
    {
        return $this->belongsToMany(Tag::class, 'order_items', 'id_order', 'id_item');
    }
    
    // Relación muchos a uno con Address
    public function address()
    {
        return $this->belongsTo(Address::class, 'id_address');
    }
}
