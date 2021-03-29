<?php

namespace App\Http\Controllers\API;

use App\User;
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
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required',
        'confirm_password' => '',
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


  public function login(Request $request)
    {
        
        $loginData = $request->validate([
          'email' => 'required|email',
          'password' => 'required',
          'confirm_password' => '',
        ]);

        $user =  DB::select('select * from users where email = :email',
         ['email' => $request->email]);
        
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
