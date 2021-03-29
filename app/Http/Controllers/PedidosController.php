<?php

namespace App\Http\Controllers;
use App\Pedidos;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;
use Validator;

class PedidosController extends ApiController
{
    public function registerPedidos(Request $request)
    {
        $validacion = Validator::make($request->all(), [
             'detalle_pedido' => 'required',
             'id_usuario' => 'required',
             'id_producto' => 'required'
        ]);
    
        if($validacion->fails()){
          return $this->sendErrors("Error de validacion", $validacion->errors(),  422);
        }

        $pedido = Pedidos::create($input);

    
        $data =    [
          "pedidos" =>$pedido
        ];
        return $this->sendResponse($data, "pedido registrado correctamente");
    
      
    }
}
