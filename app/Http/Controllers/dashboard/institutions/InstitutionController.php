<?php

namespace App\Http\Controllers\dashboard\institutions;

use App\Http\Controllers\Controller;
use App\Http\Requests\institutions\InstitutionRequest;
use App\Models\Institution;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstitutionController extends Controller
{
    /**
     * Listar todas las instituciones educativas.
     */
    public function index(): JsonResponse
    {
        $institutions = Institution::with(['department', 'city', 'specialty'])->get();
        return response()->json([
            'success' => true,
            'data' => $institutions,
        ], 200);
    }

    /**
     * Crear una nueva institución educativa.
     */
    public function store(InstitutionRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $institution = Institution::create($request->validated());
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Institución creada exitosamente.',
                'data' => $institution,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la institución: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mostrar una institución educativa específica.
     */
    public function show(Institution $institution): JsonResponse
    {
        $institution->load(['department', 'city', 'specialty']);
        return response()->json([
            'success' => true,
            'data' => $institution,
        ], 200);
    }

    /**
     * Actualizar una institución educativa.
     */
    public function update(InstitutionRequest $request, Institution $institution): JsonResponse
    {
        DB::beginTransaction();
        try {
            $institution->update($request->validated());
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Institución actualizada exitosamente.',
                'data' => $institution,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la institución: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar una institución educativa.
     */
    public function destroy(Institution $institution): JsonResponse
    {
        DB::beginTransaction();
        try {
            $institution->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Institución eliminada exitosamente.',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la institución: ' . $e->getMessage(),
            ], 500);
        }
    }
}
