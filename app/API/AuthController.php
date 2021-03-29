<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Tipo_usuario;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends ApiController
{
  public function test(Request $request)
  {
    $user = Auth::user();
    return $this->sendResponse($user, "usuarios en session");
  }
  
  public function logout()
  {
      auth()->user()->tokens->each(function ($token, $key) {
          $token->delete();
      });
      
      return response()->json('Logged out successfully', 200);
  }

  
  public function register(Request $request)
  {
      
    $validacion = Validator::make($request->all(), [
        'nombre_usuario' => 'required',
        'usuario_cedula' => 'required', 
        'email' => 'required|email',
        'password' => 'required',
        'tipo_id' => '',
    ]);

    if($validacion->fails()){
      return $this->sendErrors("usuarios en session", $validacion->errors(),  422);
    }

    $input = $request->all();
    $input["password"] = bcrypt($request->get("password"));
    $user = User::create($input);
    $token = $user->createToken("eccotech")->accessToken;

    $data =    [
      "token"=>$token,
      "user" =>$user
    ];
    return $this->sendResponse($data, "usuarios registrado correctamente");

  }

  public function getTipoUsuario(){
    
      $data = [];

      $tipo_usuario = DB::table("tipo_usuario")
          ->select("tipo_usuario.id", "tipo_usuario.nombre")
          ->get();

      $data['tipo_usuario'] = $tipo_usuario;

      return $this->sendResponse($data, "tipo_usuarios recuperadas correctamente");
  }

  public function login(Request $request)
    {
        
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
            'usuario_cedula' => 'required',
            'tipo_id' => 'required'
        ]);

        $user =  DB::select('select * from users where email = :email and usuario_cedula = :usuario_cedula and
         tipo_id = :tipo_id',
         ['email' => $request->email, 'usuario_cedula' => $request->usuario_cedula, 'tipo_id' => $request->tipo_id]);
        
         $data['usuario'] = $user;
        
         if(count($data['usuario']) <= 0){
           return $this->sendErrors("Informacion invalida", "datos incorrectos",  404);
         }

         $userLogin = [
          'email' => $loginData['email'],
          'password' => $loginData['password']
         ];
       
       if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);
    }
}
