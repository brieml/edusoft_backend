<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    //
    public function sendPin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generar el PIN de 6 dígitos
        $pin = random_int(100000, 999999);

        // Encriptar el PIN
        $hashedPin = Hash::make($pin);

        // Insertar o actualizar el token en password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $hashedPin, 'created_at' => now()]
        );

        // Enviar correo personalizado
        $user = \App\Models\User::where('email', $request->email)->first();
        Mail::send('emails.reset_pin', ['user' => $user, 'pin' => $pin, 'expiresIn' => 10], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Tu PIN de recuperación');
        });

        return response()->json(['message' => 'PIN enviado al correo.']);
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'pin' => 'required|digits:6',
        ]);

        // Obtener el token de la tabla
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        // Validar si el token existe y el PIN coincide
        if ($tokenData && Hash::check($request->pin, $tokenData->token) && Carbon::parse($tokenData->created_at)->addMinutes(15)->isFuture()) {
            return response()->json(['message' => 'PIN válido.']);
        }

        return response()->json(['error' => 'PIN inválido o expirado.'], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'pin' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ]);

        // Obtener el token de la tabla
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        // Validar si el token existe, el PIN coincide y no ha expirado
        if ($tokenData && Hash::check($request->pin, $tokenData->token) && Carbon::parse($tokenData->created_at)->addMinutes(15)->isFuture()) {
            // Actualizar contraseña del usuario
            $user = \App\Models\User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            // Eliminar el token de la tabla
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json(['message' => 'Contraseña restablecida exitosamente.']);
        }

        return response()->json(['error' => 'PIN inválido o expirado.'], 400);
    }
}
