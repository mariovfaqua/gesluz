<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'material',
        'color',
        'marca',
        'stock'
    ];

    public static $materials = [
        'Plástico',
        'Metal',
        'Madera',
        'Vidrio',
    ];
}
