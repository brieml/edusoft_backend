<?php

namespace App\Http\Requests\institutions;

use Illuminate\Foundation\Http\FormRequest;

class InstitutionRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para la solicitud.
     */
    public function rules(): array
    {
        return [
            'dane_code' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'nit' => 'required|string|max:20|unique:institutions,nit,' . ($this->institution->id ?? 'NULL') . ',id',
            'principal_name' => 'required|string|max:255',
            'principal_id' => 'nullable|string|max:20',
            'sector' => 'required|in:PRIVADO,PÚBLICO',
            'calendar' => 'required|in:A,B',
            'entity_type' => 'nullable|string|max:100',
            'department_id' => 'required|exists:departments,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'nullable|string|max:255',
            'rural_area' => 'nullable|string|max:100',
            'zone' => 'nullable|in:URBANA,RURAL',
            'phones' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:100',
            'service_provider' => 'nullable|string|max:255',
            'branch_count' => 'nullable|integer',
            'education_core_no' => 'nullable|string|max:50',
            'core_address' => 'nullable|string|max:255',
            'core_phone' => 'nullable|string|max:100',
            'creation_decree' => 'nullable|string|max:255',
            'secretary_name' => 'nullable|string|max:255',
            'secretary_id' => 'nullable|string|max:20',
            'approval_resolution' => 'nullable|string|max:255',
            'motto' => 'nullable|string|max:255',
            'logo_path' => 'nullable|string|max:255',
            'specialty_id' => 'nullable|exists:specialties,id',
        ];
    }

    /**
     * Mensajes personalizados para errores de validación.
     */
    public function messages(): array
    {
        return [
            'dane_code.required' => 'El código DANE es obligatorio.',
            'name.required' => 'El nombre de la institución es obligatorio.',
            'nit.required' => 'El NIT de la institución es obligatorio.',
            'nit.unique' => 'El NIT ingresado ya está registrado.',
            'principal_name.required' => 'El nombre del rector o director es obligatorio.',
            'sector.required' => 'El sector de la institución es obligatorio.',
            'sector.in' => 'El sector debe ser PRIVADO o PÚBLICO.',
            'calendar.required' => 'El calendario de la institución es obligatorio.',
            'calendar.in' => 'El calendario debe ser A o B.',
            'department_id.required' => 'El departamento es obligatorio.',
            'department_id.exists' => 'El departamento seleccionado no existe.',
            'city_id.required' => 'La ciudad es obligatoria.',
            'city_id.exists' => 'La ciudad seleccionada no existe.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'specialty_id.exists' => 'La especialidad seleccionada no existe.',
        ];
    }
}
