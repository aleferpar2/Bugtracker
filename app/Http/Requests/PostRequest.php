<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Post;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->isMethod('POST')) {
            return true; // Cualquier usuario autenticado puede crear posts
        }

        $post = $this->route('post');
        return $this->user()->can('update', $post);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(Post::STATUSES))],
            'priority' => ['required', 'string', 'in:' . implode(',', array_keys(Post::PRIORITIES))],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id']
        ];

        // Reglas específicas para actualización
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['title'][] = 'unique:posts,title,' . $this->route('post')->id;
        } else {
            $rules['title'][] = 'unique:posts,title';
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
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede tener más de 255 caracteres.',
            'title.unique' => 'Ya existe un post con este título.',
            'description.required' => 'La descripción es obligatoria.',
            'description.min' => 'La descripción debe tener al menos 10 caracteres.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.',
            'priority.required' => 'La prioridad es obligatoria.',
            'priority.in' => 'La prioridad seleccionada no es válida.',
            'assigned_to.exists' => 'El usuario asignado no existe.',
            'categories.exists' => 'Una de las categorías seleccionadas no existe.',
            'categories.*.exists' => 'Una de las categorías seleccionadas no existe.'
        ];
    }
} 