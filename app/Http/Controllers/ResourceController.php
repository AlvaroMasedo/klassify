<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function entry(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role === 'ADMIN') {
            return redirect()->route('admin.resources.create');
        }

        if ($user->role === 'TEACHER') {
            if ($user->teacher_status === 'ACTIVE') {
                return redirect()->route('teacher.resources.create');
            }

            return redirect()->route('teacher.pending')->with('error', 'Tu cuenta de profesor está pendiente de aprobación.');
        }

        return redirect()->route('feed')->with('error', 'No tienes permiso para publicar recursos.');
    }

    public function create(Request $request): View
    {
        $scope = $this->resolveScope($request);

        return view('resources.create', [
            'scope' => $scope,
            'scopeLabel' => $scope === 'admin' ? 'Administrador' : 'Profesor verificado',
            'storeRoute' => route($scope . '.resources.store'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $scope = $this->resolveScope($request);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'resource_file' => ['required', 'file', 'max:80000'],
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título no es válido.',
            'title.max' => 'El título no puede superar los 255 caracteres.',
            'description.string' => 'La descripción no es válida.',
            'description.max' => 'La descripción no puede superar los 1000 caracteres.',
            'resource_file.required' => 'Debes seleccionar un archivo.',
            'resource_file.file' => 'El archivo seleccionado no es válido.',
            'resource_file.max' => 'El archivo no puede superar los 80 MB.',
        ]);

        $file = $request->file('resource_file');
        $fileName = Str::slug($validated['title']) . '-' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
        $storedPath = $file->storePubliclyAs('resources/' . $scope, $fileName, 'public');

        return back()->with('status', 'Recurso subido correctamente.')->with('stored_resource', Storage::url($storedPath));
    }

    private function resolveScope(Request $request): string
    {
        return $request->routeIs('admin.*') ? 'admin' : 'teacher';
    }
}
