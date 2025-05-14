<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    protected $table = 'productos'; // Nombre de la tabla en la base de datos
    protected $fillable = [
        'nombre',
        'sku',
        'precio',
        'descripcion',
        'imagen',
    ];
}
