<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Models\User;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        
        try {
            // Encriptamos la contraseña
            $hashPassword = bcrypt($validatedData['password']);

            $newUser = new User();

            $newUser->role_id = $validatedData['role_id'];
            $newUser->names = $validatedData['names'];
            $newUser->last_names = $validatedData['last_names'];
            $newUser->username = $validatedData['username'];
            $newUser->email = $validatedData['email'];
            $newUser->password = $hashPassword;

            $newUser->save();

            return response()->json([
                'message' => 'Usuario registrado exitosamente.',
                'data' => $newUser,
                'status' => 'success',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al registrar el usuario.',
                'data' => null,
                'status' => 'error',
            ], 500);
        }
    }
    
    public function login(LoginRequest $request)
    {
        // Request para validar los datos que se envían
        $validatedData = $request->validated();

        try {
            // Buscamos el usuario por el correo electrónico y estado activo
            $user = User::where('email', $request->email)
                        ->where('status', true)
                        ->first();

            // Recolectamos las credenciales enviadas
            $credentials = array(
                'email' => $request->email,
                'password' => $request->password,
            );

            // Intentamos autenticar al usuario con las credenciales
            if (Auth::attempt($credentials) == false || $user == NULL) {
                return response()->json([
                    'message' => 'Credenciales inválidas o usuario no encontrado.',
                    'data' => null,
                    'status' => 'error',
                ], 401);
            }

            // Obtiene el usuario autenticado
            // $loggedUser = Auth::user();
            $loggedUser = $request->user();
            
            // Creamos token de acceso
            $tokenResult = $loggedUser->createToken('Admin Access Token');

            // Almacenamos el token de acceso
            $token = $tokenResult->token;

            // Cambiamos la fecha de expiración del token
            // $token->expires_at = Carbon::now()->addhours(3);
            
            // Guardamos el token en la base de datos
            $token->save();

            return response()->json([
                'message' => 'Inicio de sesión exitoso.',
                'data' => [
                    'user' => $loggedUser, // Información del usuario autenticado
                    'access_token' => $tokenResult->accessToken, // Token
                    'token_type' => 'Bearer', // Tipo de token
                    'expires_at' => $tokenResult->token->expires_at, // Fecha de expiración
                ],
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al iniciar sesión.',
                'data' => null,
                'error' => $th->getMessage(),
                'status' => 'error',
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            $data = $request->user();

            $user = User::where('id', $data->id)
                        ->first();

            if ($user == NULL) {
                return response()->json([
                    'message' => 'Usuario no encontrado.',
                    'data' => null,
                    'status' => 'error',
                ], 404);
            }

            return response()->json([
                'message' => 'Perfil del usuario obtenido exitosamente.',
                'data' => $user,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener el perfil del usuario.',
                'data' => null,
                'status' => 'error',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            // Revocamos el token de acceso del usuario
            $user->token()->revoke();

            return response()->json([
                'message' => 'Cierre de sesión exitoso.',
                'data' => null,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al cerrar sesión.',
                'data' => null,
                'status' => 'error',
            ], 500);
        }
    }

    // public function sendResetLink() {}
    // public function resetPassword() {}
}
