<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function funLogin(Request $request)
    { // va recibir datos
        // validar
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        //verificar
        if (!Auth::attempt($credenciales)) {
            return response()->json(["message" => "No Autenticado"], 401);
        }
        //generar token
        $usuario = Auth::user();
        $token = $usuario->createToken("token personal")->plainTextToken;

        //responder
        return response()->json([
            "access_token" => $token,
            "type_token" => "Bearer",
            "usuario" => $usuario
        ]);
    }

    public function funRegistro(Request $request)
    { // va recibir datos
        // validar datos
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required",
            "c_password" => "required|same:password"
        ]);

        // guardar usuario
        $usuario = new User;
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = bcrypt($request->password);
        $usuario->save();

        return response()->json(["mensaje" => "Usuario Registrado"], 201);
    }

    public function funPerfil()
    { // no recibe ningun dato
        return Auth::user(); // para recuperar los datos

    }

    public function funSalir()
    { // no recibe ningun dato
        Auth::user()->tokens()->delete(); // RECUPERA Y ACCEDE A TOKENS Y LUEGO ELIMINA
        return response()->json(["message" => "SALIO"]); //RETORNA EL MENSAJE 

    }
}
