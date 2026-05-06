<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nickname' => [
                'required',
                'string',
                'min:3',
                'max:60',
                Rule::unique('users', 'nickname')->ignore($this->user()->id),
            ],
            'current_password' => [
                'nullable',
                'required_with:password',
                'current_password',
            ],
            'password' => [
                'nullable',
                'required_with:current_password',
                'string',
                'min:8',
                'max:100',
                'confirmed',
            ],
            'password_confirmation' => [
                'nullable',
                'required_with:password',
            ],
            'specialization' => [
                'nullable',
                'string',
                Rule::exists('courses', 'name'),
            ],
            'is_private' => [
                'nullable',
                'boolean',
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'foto_perfil' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/x-png',
                'extensions:jpg,jpeg,png',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nickname.required' => 'El nombre de usuario es obligatorio.',
            'nickname.min' => 'El nombre de usuario debe tener al menos :min caracteres.',
            'nickname.max' => 'El nombre de usuario no puede superar los :max caracteres.',
            'nickname.unique' => 'Este nombre de usuario ya está en uso.',

            'current_password.required_with' => 'Debes escribir tu contraseña actual para cambiar la contraseña.',
            'current_password.current_password' => 'La contraseña actual no es correcta.',

            'password.required_with' => 'Debes escribir una nueva contraseña.',
            'password.min' => 'La nueva contraseña debe tener al menos :min caracteres.',
            'password.max' => 'La nueva contraseña no puede superar los :max caracteres.',
            'password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',

            'password_confirmation.required_with' => 'Debes repetir la nueva contraseña.',

            'specialization.exists' => 'La especialización seleccionada no es válida.',

            'description.max' => 'La descripción no puede superar los :max caracteres.',

            'foto_perfil.file' => 'La foto de perfil debe ser un archivo válido.',
            'foto_perfil.mimetypes' => 'La foto de perfil debe ser JPG, JPEG o PNG.',
            'foto_perfil.extensions' => 'La foto de perfil debe tener extensión JPG, JPEG o PNG.',
            'foto_perfil.max' => 'La foto de perfil no puede pesar más de 10 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nickname' => 'nombre de usuario',
            'current_password' => 'contraseña actual',
            'password' => 'nueva contraseña',
            'password_confirmation' => 'confirmación de contraseña',
            'specialization' => 'especialización',
            'description' => 'descripción',
            'foto_perfil' => 'foto de perfil',
        ];
    }
}
