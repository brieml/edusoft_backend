<?php

namespace App\Http\Requests\specialties;

use Illuminate\Foundation\Http\FormRequest;

class SpecialtyRequest extends FormRequest
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
            'code' => 'required|string|max:100|unique:specialties,code,' . ($this->specialty->id ?? 'NULL') . ',id',
            'name' => 'required|string|max:100|unique:specialties,name,' . ($this->specialty->id ?? 'NULL') . ',id',
            'description' => 'required|string|max:100|unique:specialties,description,' . ($this->specialty->id ?? 'NULL') . ',id',
        ];
    }

    /**
     * Mensajes personalizados de validación.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'El código es obligatorio.',
            'code.unique' => 'El código ya está en uso.',
            'name.required' => 'El nombre es obligatorio.',
            'name.unique' => 'El nombre ya está en uso.',
            'description.required' => 'La descripción es obligatoria.',
            'description.unique' => 'La descripción ya está en uso.',
        ];
    }
}
