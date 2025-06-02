<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolutionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->isMethod('POST')) {
            return true; // Cualquier usuario autenticado puede crear soluciones
        }

        $solution = $this->route('solution');
        return $this->user()->can('update', $solution);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'content' => ['required', 'string', 'min:10', 'max:5000'],
            'post_id' => ['required', 'exists:posts,id'],
            'status' => ['required', 'string', 'in:pending,approved,rejected']
        ];

        // Solo permitir cambiar el estado si es una actualización
        if ($this->isMethod('PATCH') && $this->has('status')) {
            $rules['status'] = ['required', 'string', 'in:pending,approved,rejected'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content.required' => 'El contenido de la solución es obligatorio.',
            'content.min' => 'La solución debe tener al menos 10 caracteres.',
            'content.max' => 'La solución no puede tener más de 5000 caracteres.',
            'post_id.required' => 'El post es obligatorio.',
            'post_id.exists' => 'El post seleccionado no existe.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.'
        ];
    }
} 