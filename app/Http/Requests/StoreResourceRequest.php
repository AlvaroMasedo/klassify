<?php

namespace App\Http\Requests;

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
                'max:81920', // 80 MB en KB
                'mimes:pdf,doc,docx,ppt,pptx,mp4,mp3,jpeg,png'
            ],
        ];
    }

    public function messages(): array
   {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede superar los 200 caracteres.',

            'course_id.required' => 'Debes seleccionar un curso.',
            'subject_id.required' => 'Debes seleccionar una materia.',

            'resource_file.required' => 'Debes seleccionar un archivo.',
            'resource_file.file' => 'El archivo no es válido.',
            'resource_file.max' => 'El archivo no puede superar los 80 MB.',
            'resource_file.mimes' => 'El archivo debe ser PDF, Word, PowerPoint, imagen, vídeo o audio.',
        ];
    }
}
