<?php

namespace App\Http\Requests\Auth;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

            'course_id' => [
                Rule::requiredIf(fn () => $this->input('role') === 'TEACHER'),
                'nullable',
                'integer',
                Rule::exists('courses', 'id'),
            ],

            'name_institucion' => [
                Rule::requiredIf(fn () => $this->input('role') === 'TEACHER'),
                'nullable',
                'string',
                'max:255',
            ],
            'direccion' => [
                Rule::requiredIf(fn () => $this->input('role') === 'TEACHER'),
                'nullable',
                'string',
                'max:255',
            ],
            'email_institucional' => [
                Rule::requiredIf(fn () => $this->input('role') === 'TEACHER'),
                'nullable',
                'email',
                'max:255',
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
            'terms.accepted' => 'Debes aceptar los términos de uso.',
            'course_id.required' => 'El curso es obligatorio para profesores.',
            'course_id.exists' => 'El curso seleccionado no es válido.',
            'name_institucion.required' => 'El nombre de la institución es obligatorio.',
            'direccion.required' => 'La dirección de la institución es obligatoria.',
            'email_institucional.required' => 'El email institucional es obligatorio.',
            'email_institucional.email' => 'Introduce un email institucional válido.',
        ];
    }
}