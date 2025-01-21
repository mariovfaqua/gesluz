<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $table='tags';
    protected $fillable= [
        'nombre'
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_tags', 'id_tag', 'id_item');
    }
}
