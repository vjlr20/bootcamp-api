<?php

namespace App\Http\Controllers;

use Auth;
use Notification; // Para enviar notificaciones
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password; // Para manejar el restablecimiento de contraseñas
use Illuminate\Support\Facades\Hash; // Para hashear contraseñas
use Illuminate\Auth\Events\PasswordReset; // Evento para el restablecimiento de contraseñas

use App\Models\User;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;

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
    
    public function updateProfile(Request $request)
    {
        try { 
            // Obtenemos el usuario autenticado
            $currentUser = $request->user();

            // Obtenemos el usuario original de la base de datos
            $user = User::where('id', $currentUser->id)->first();

            // Validamos si el usuario existe o no
            if ($user == NULL) {
                return response()->json([
                    'message' => 'Usuario no encontrado.',
                    'data' => null,
                    'status' => 'error',
                ], 404);
            }

            // Actualizamos los campos del usuario
            $user->names = ($request->names == NULL) ? $user->names : $request->names;
            $user->last_names = ($request->last_names == NULL) ? $user->last_names : $request->last_names;
            $user->username = ($request->username == NULL) ? $user->username : $request->username;
            $user->email = ($request->email == NULL) ? $user->email : $request->email;

            // Persistimos los cambios en la base de datos
            $user->save();
            
            // Retornamos la respuesta exitosa
            return response()->json([
                'message' => 'Perfil del usuario actualizado exitosamente.',
                'data' => $user,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al actualizar el perfil del usuario.',
                'data' => null,
                'status' => 'error',
            ], 500);
        }
    }

    // Envío de enlace para cambiar la contraseña
    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $validatedData = $request->validated();

        try {
            // Obtenemos el usuario por el correo electrónico
            $user = User::where('email', $request->email)->first();    

            if ($user == NULL) {
                return response()->json([
                    'message' => 'El correo electrónico no se encuentra registrado.',
                    'data' => null,
                    'status' => 'error',
                ], 404);
            }

            $input = array(
                'email' => $request->email
            );

            // Enviamos el enlace de restablecimiento de contraseña
            $send = Password::sendResetLink($input);

            // Si fue enviado o no
            if ($send != Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' => 'No se pudo enviar el enlace de restablecimiento de contraseña.',
                    'data' => null,
                    'status' => 'error',
                ], 500);
            }

            return response()->json([
                'message' => 'Enlace de restablecimiento de contraseña enviado exitosamente.',
                'data' => null,
                'status' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al enviar el enlace de restablecimiento de contraseña.',
                'data' => null,
                'error' => array(
                    'message' => $th->getMessage(),
                    'line' => $th->getLine(),
                ),
                'status' => 'error',
            ], 500);
        }
    }

    // El cambio de la contraseña
    public function resetPassword() {}
}
