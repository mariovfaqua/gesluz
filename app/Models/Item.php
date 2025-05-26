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
        'distribucion',
        'tipo',
        'alto',
        'ancho',
        'stock',
        'disponibilidad',
        'id_brand',
    ];

    /** Relación muchos a muchos con Tag */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'item_tags', 'id_item', 'id_tag');
    }

    /** Relación muchos a muchos con Order */
    public function orders()
    {
        return $this->belongsToMany(Tag::class, 'order_items', 'id_item', 'id_order');
    }

    // Relación uno a muchos con las imágenes
    public function images()
    {
        return $this->hasMany(Image::class, 'id_item');
    }

    // Devuelve un listado con los tipos que coincide con la migración
    public static function getTipos()
    {
        return ['Plafón', 'Sobremesa', 'Auxiliar', 'Colgante', 'Empotrada', 'De pie', 'Foco', 'Tira led', 'Otro'];
    }

    // Devuelve un listado de las distribuciones que coincide con la migración
    public static function getDistribucion()
    {
        return ['Dormitorio', 'Baño', 'Jardín', 'Cocina', 'Salón', 'Otro'];
    }
}
