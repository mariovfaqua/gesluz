<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item_Tag extends Model
{
    use HasFactory;
    protected $table='item_tags';
    protected $fillable= [
        'item_id',
        'tag_id',
    ];
}
