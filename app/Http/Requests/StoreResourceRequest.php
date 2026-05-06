<?php

namespace App\Http\Requests;

use App\Services\Resources\ResourceTypeResolver;
use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'course_id' => 'required|integer|exists:courses,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'resource_file' => [
                'required',
                'file',
                'max:81920',
                'mimes:' . ResourceTypeResolver::getMimeValidationRule(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede superar los 200 caracteres.',

            'course_id.required' => 'Debes seleccionar un curso.',
            'course_id.exists' => 'El curso seleccionado no existe.',
            'subject_id.required' => 'Debes seleccionar una materia.',
            'subject_id.exists' => 'La materia seleccionada no existe.',

            'resource_file.required' => 'Debes seleccionar un archivo.',
            'resource_file.file' => 'El archivo no es válido.',
            'resource_file.max' => 'El archivo no puede superar los 80 MB.',
            'resource_file.mimes' => 'El formato del archivo no está permitido. Acepta: PDF, Word, PowerPoint, imágenes (JPG, PNG) y vídeos/audio (MP4, MP3).',
            
        ];
    }
}
