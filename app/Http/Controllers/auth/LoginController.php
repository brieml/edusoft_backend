<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Generar una clave única para la limitación de tasa
        $rateLimiterKey = strtolower($request->input('username')) . '|' . $request->ip();

        // Verificar si el usuario ha excedido el límite de intentos (5 intentos)
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            $secondsRemaining = RateLimiter::availableIn($rateLimiterKey);

            return response()->json([
                'mensaje' => "Demasiados intentos. Por favor, inténtelo de nuevo en {$secondsRemaining} segundos."
            ], 429);
        }

        // Verificar si la credencial es un correo electrónico o un número de documento
        $credentials = $request->input('username');
        $isEmail = filter_var($credentials, FILTER_VALIDATE_EMAIL);

        // Preparar las credenciales de autenticación
        $authCredentials = $isEmail
            ? ['email' => $credentials, 'password' => $request->input('password')]
            : ['document_number' => $credentials, 'password' => $request->input('password')];

        // Intentar autenticar al usuario
        if (Auth::attempt($authCredentials)) {
            // Limpiar el contador de intentos si el inicio de sesión es exitoso
            RateLimiter::clear($rateLimiterKey);
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            // Obtener roles y permisos del usuario
            $roles = $user->getRoleNames();

            // Obtener los atributos del usuario (suponiendo que el método getUserAttributes está definido en el controlador)
            $attributes = $this->getUserAttributes($user);

            if (!$user->email_verified_at) {
                return response()->json([
                    'message' => 'Correo no verificado. Por favor, verifica tu correo electrónico.'
                ], 403);
            }

            // Devolver una respuesta exitosa con el token, roles y atributos del usuario
            return response()->json([
                'mensaje' => 'Inicio de sesión exitoso',
                'token' => $token,
                'tipo_token' => 'Bearer',
                'roles' => $roles,
                'atributos' => $attributes,
            ]);
        }

        // Incrementar el contador de intentos si la autenticación falla
        RateLimiter::hit($rateLimiterKey);

        return response()->json([
            'mensaje' => 'Las credenciales proporcionadas son incorrectas.'
        ], 401);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        // Revocar todos los tokens del usuario actual
        $user->tokens()->delete();

        // Verificar si se han eliminado los tokens
        $tokensExist = $user->tokens()->exists();

        if ($tokensExist) {
            return response()->json([
                'mensaje' => 'Error al cerrar sesión. No se pudieron eliminar los tokens.'
            ], 500);
        } else {
            return response()->json([
                'mensaje' => 'Cierre de sesión exitoso'
            ]);
        }
    }

    // Function to get specific user attributes
    private function getUserAttributes($user)
    {
        // Return the desired user attributes
        return $user->only(['id', 'email', 'name', 'created_at']); // Adjust the fields as needed
    }

    public function sendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Generar un código de verificación aleatorio
        $verificationCode = Str::random(6);

        // Encriptar el código de verificación
        $hashedPin = Hash::make($verificationCode);

        // Definir tiempo de expiración (10 minutos a partir de ahora)
        $expiresIn = now()->addMinutes(10);

        // Almacenar el código de verificación encriptado y su tiempo de expiración
        $user->verification_code = $hashedPin;
        $user->verification_code_expires_at = $expiresIn;
        $user->save();

        // Enviar el código al correo del usuario
        Mail::send('emails.verification', ['user' => $user, 'code' => $verificationCode, 'expiresIn' => 10], function ($message) use ($user) {
            $message->to($user->email)->subject('Código de Verificación');
        });

        return response()->json([
            'message' => 'Código de verificación enviado',
            'expires_in' => $expiresIn->toDateTimeString() // Convertir a cadena
        ]);
    }


    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Verificar si el código ha expirado
        if ($user->verification_code_expires_at && now()->greaterThan($user->verification_code_expires_at)) {
            return response()->json(['message' => 'El código de verificación ha expirado'], 400);
        }

        // Verificar si el código de verificación ingresado coincide
        if (!Hash::check($request->verification_code, $user->verification_code)) {
            return response()->json(['message' => 'Código de verificación inválido'], 400);
        }

        // Marcar el usuario como verificado
        $user->email_verified_at = now();
        $user->verification_code = null; // Limpiar el código de verificación
        $user->verification_code_expires_at = null; // Limpiar el tiempo de expiración
        $user->save();

        return response()->json([
            'message' => 'Correo electrónico verificado exitosamente'
        ], 200);
    }


}
