<?php

namespace App\Http\Controllers;
use App\Producto;
use App\Http\Controllers\API\ApiController;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductoController extends ApiController
{
    public function listProductos()
    {
        $productos = DB::select('select * from productos');
        if($productos === null){
            return $this->sendErrors("OOO", ["no hay datos"], 422);
        }

        return $productos;
    }

    public function register(Request $request)
    {
        
      $validacion = Validator::make($request->all(), [
          'titulo' => 'required',
          'imagen' => 'required',
          'descripcion' => 'required',
          'valor_unitario' => 'required'
      ]);
  
      if($validacion->fails()){
        return $this->sendErrors("Error de validacion", $validacion->errors(),  422);
      }
  
      $input = $request->all();
    
      $producto = Producto::create($input);
  
      $data =  [     
        "producto" =>$producto
      ];
      return $this->sendResponse($data, "producto registrado correctamente");
  
    }
}
