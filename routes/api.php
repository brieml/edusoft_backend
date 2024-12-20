<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\auth\ResetPasswordController;
use App\Http\Controllers\dashboard\city\CityController;
use App\Http\Controllers\dashboard\department\DepartmentController;
use App\Http\Controllers\dashboard\documenttype\DocumentTypeController;
use App\Http\Controllers\dashboard\institutions\InstitutionController;
use App\Http\Controllers\dashboard\specialties\SpecialtyController;
use App\Http\Controllers\upload\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta para iniciar sesión en el sistema (Autenticación de usuarios)
Route::post('authenticate/login', [LoginController::class, 'login']);

// Ruta para enviar un correo electrónico de verificación
Route::post('/send-verification-email', [LoginController::class, 'sendVerificationEmail']);

// Ruta para verificar un correo electrónico mediante un token o código
Route::post('/verify-email', [LoginController::class, 'verifyEmail']);

// Ruta para enviar un PIN para restablecer la contraseña
Route::post('/password/send-pin', [ResetPasswordController::class, 'sendPin']);

// Ruta para verificar el PIN enviado para restablecer la contraseña
Route::post('/password/verify-pin', [ResetPasswordController::class, 'verifyPin']);

// Ruta para restablecer la contraseña usando el PIN verificado
Route::post('/password/reset-password', [ResetPasswordController::class, 'resetPassword']);

// Rutas protegidas por middleware de autenticación (requieren un token válido)
Route::middleware('auth:sanctum')->group(function () {
    // Ruta para cerrar sesión (revoca el token de autenticación)
    Route::post('authenticate/logout', [LoginController::class, 'logout']);


    Route::apiResource('institutions', InstitutionController::class);


    // Ruta para la gestión de especialidades
    Route::apiResource('/dashboard/specialties', SpecialtyController::class);

    // Ruta para la gestión de departamentos
    Route::apiResource('/dashboard/departments', DepartmentController::class);

    // Ruta para la gestión de ciudades
    Route::apiResource('/dashboard/cities', CityController::class);

    // Ruta para la gestión de tipos de documentos
    Route::apiResource('/dashboard/document-types', DocumentTypeController::class);


    Route::post('/upload', [UploadController::class, 'upload'])->name('files.upload');

});

