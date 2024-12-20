<?php

namespace App\Http\Controllers\dashboard\specialties;

use App\Http\Controllers\Controller;
use App\Http\Requests\specialties\SpecialtyRequest;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    /**
     * Listar todas las especialidades.
     */
    public function index(): JsonResponse
    {
        $specialties = Specialty::all();
        return response()->json([
            'success' => true,
            'data' => $specialties,
        ], 200);
    }

    /**
     * Almacenar una nueva especialidad.
     */
    public function store(SpecialtyRequest $request): JsonResponse
    {
        $specialty = Specialty::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Especialidad creada exitosamente.',
            'data' => $specialty,
        ], 201);
    }

    /**
     * Mostrar una especialidad específica.
     */
    public function show(Specialty $specialty): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $specialty,
        ], 200);
    }

    /**
     * Actualizar una especialidad existente.
     */
    public function update(SpecialtyRequest $request, Specialty $specialty): JsonResponse
    {
        $specialty->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Especialidad actualizada exitosamente.',
            'data' => $specialty,
        ], 200);
    }

    /**
     * Eliminar una especialidad.
     */
    public function destroy(Specialty $specialty): JsonResponse
    {
        $specialty->delete();
        return response()->json([
            'success' => true,
            'message' => 'Especialidad eliminada exitosamente.',
        ], 200);
    }
}
