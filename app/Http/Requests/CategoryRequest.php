<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'icon' => ['nullable', 'string', 'max:50']
        ];

        // Reglas específicas para actualización
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['name'][] = 'unique:categories,name,' . $this->route('category')->id;
        } else {
            $rules['name'][] = 'unique:categories,name';
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
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 50 caracteres.',
            'name.unique' => 'Ya existe una categoría con este nombre.',
            'description.max' => 'La descripción no puede tener más de 255 caracteres.',
            'color.required' => 'El color es obligatorio.',
            'color.regex' => 'El color debe ser un código hexadecimal válido (ej: #FF0000).',
            'icon.max' => 'El nombre del icono no puede tener más de 50 caracteres.'
        ];
    }
} 