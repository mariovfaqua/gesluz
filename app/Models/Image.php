<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $fillable = [
        'url',
    ];

    // Relación muchos a uno con el item
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item');
    }
}
