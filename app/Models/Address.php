<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = 'addresses';
    protected $fillable = [
        'nombre',
        'linea_1',
        'linea_2',
        'provincia',
        'ciudad',
        'pais',
        'codigo_postal',
        'primaria',
        'id_user',
    ];
}
