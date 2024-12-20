<?php

namespace App\Http\Controllers\upload;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UploadController extends Controller
{
    //

    public function upload(Request $request)
    {
        try {
            // Validación de la solicitud
            $request->validate([
                'filepath' => 'required|file|max:10240', // 10MB máximo
                'reference_code' => 'required|string',
            ]);

            // Preparación de la información del archivo
            $file = $request->file('filepath');
            $referenceCode = $request->input('reference_code');
            $extension = $file->getClientOriginalExtension();
            $filename = $referenceCode . '.' . $extension;

            // Almacenamiento del archivo
            // Se usa una sola ruta y un solo método de almacenamiento
            $path = 'public/upload/' . $referenceCode;
            Storage::putFileAs($path, $file, $filename);

            if ($extension == 'pdf') {
                // Construir la ruta para la respuesta
                $filePath = 'storage/upload/' . $referenceCode . '/documents/' . $filename;

                $file->move('storage/upload/' . $referenceCode . '/documents/', $filename);
            } else {
                // Construir la ruta para la respuesta
                $filePath = 'storage/upload/' . $referenceCode . '/images/' . $filename;

                $file->move('storage/upload/' . $referenceCode . '/images/', $filename);
            }

            // Respuesta exitosa
            return response()->json([
                'message' => 'Se ha almacenado correctamente el archivo',
                'data' => $filePath,
            ], Response::HTTP_OK);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error de validación: ' . $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);

        } catch (Exception $e) {
            Log::error('Error al subir el archivo: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error al subir el archivo. Por favor, intente de nuevo.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
