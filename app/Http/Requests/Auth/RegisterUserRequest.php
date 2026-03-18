<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Closure;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:50', 'unique:users,nickname'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!preg_match('/[a-z]/', $value)) {
                        $fail('La contraseña debe tener al menos una letra minúscula.');
                    }

                    if (!preg_match('/[A-Z]/', $value)) {
                        $fail('La contraseña debe tener al menos una letra mayúscula.');
                    }

                    if (!preg_match('/[0-9]/', $value)) {
                        $fail('La contraseña debe tener al menos un número.');
                    }

                    if (!preg_match('/[\W_]/', $value)) {
                        $fail('La contraseña debe tener al menos un símbolo.');
                    }
                },
            ],
            'role' => ['required', Rule::in(['STUDENT', 'TEACHER'])],
            'terms' => ['accepted'],

            'specialization' => [
                Rule::requiredIf(fn() => $this->input('role') === 'TEACHER'),
                'nullable',
                'string',
                'max:255',
            ],

            'institution_id' => [
                Rule::requiredIf(fn() => $this->input('role') === 'TEACHER'),
                'nullable',
                'integer',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'surname.required' => 'El apellido es obligatorio.',
            'nickname.required' => 'El nickname es obligatorio.',
            'nickname.unique' => 'Este nickname ya está en uso.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Introduce un email válido.',
            'email.unique' => 'Este email ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.characters' => 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un caracter especial.',
            'terms.accepted' => 'Debes aceptar los términos de uso.',
            'specialization.required' => 'La especialización es obligatoria para profesores.',
        ];
    }
}
