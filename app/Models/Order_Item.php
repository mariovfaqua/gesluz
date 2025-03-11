<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Item extends Model
{
    use HasFactory;
    protected $table='order_items';
    protected $fillable= [
        'id_order',
        'id_item',
        'cantidad'
    ];

     // Relación con Order
     public function order()
     {
         return $this->belongsTo(Order::class, 'id_order');
     }
 
     // Relación con Item
     public function item()
     {
         return $this->belongsTo(Item::class, 'id_item');
     }

}
