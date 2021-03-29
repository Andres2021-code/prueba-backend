<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    protected $table = "pedidos";



    protected $fillable = [
        'id_users',
        'id_producto',
        'detalle_pedido'  
    ];
}
