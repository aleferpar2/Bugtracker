<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'read' => ['boolean'],
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
            'type.required' => 'El tipo de notificación es requerido.',
            'type.string' => 'El tipo de notificación debe ser texto.',
            'type.max' => 'El tipo de notificación no puede tener más de 255 caracteres.',
            'message.required' => 'El mensaje es requerido.',
            'message.string' => 'El mensaje debe ser texto.',
            'read.boolean' => 'El estado de lectura debe ser verdadero o falso.',
        ];
    }
} 