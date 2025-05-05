<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = 'addresses';
    protected $fillable = [
        'destinatario',
        'provincia',
        'ciudad',
        'pais',
        'codigo_postal',
        'primaria',
        'id_user',
    ];

    // Relación uno a muchos con Orders
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_address');
    }
}
