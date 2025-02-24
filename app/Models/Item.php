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
        'material',
        // 'color',
        'stock',
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
}
