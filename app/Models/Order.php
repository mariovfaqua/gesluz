<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table='orders';
    protected $fillable= [
        'fecha',
        'precio_total',
        'estatus',
        'id_user',
        'id_address',
    ];
}
