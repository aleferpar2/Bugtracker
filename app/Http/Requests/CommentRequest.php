<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->isMethod('POST')) {
            return true; // Cualquier usuario autenticado puede crear comentarios
        }

        $comment = $this->route('comment');
        return $this->user()->can('update', $comment);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:3', 'max:1000'],
            'post_id' => ['required', 'exists:posts,id'],
            'parent_id' => ['nullable', 'exists:comments,id']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content.required' => 'El contenido del comentario es obligatorio.',
            'content.min' => 'El comentario debe tener al menos 3 caracteres.',
            'content.max' => 'El comentario no puede tener más de 1000 caracteres.',
            'post_id.required' => 'El post es obligatorio.',
            'post_id.exists' => 'El post seleccionado no existe.',
            'parent_id.exists' => 'El comentario padre seleccionado no existe.'
        ];
    }
} 