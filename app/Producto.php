<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "productos";



    protected $fillable = [
        'imagen',
        'titulo', 
        'descripcion',
       'valor_unitario'  
    ];

}
