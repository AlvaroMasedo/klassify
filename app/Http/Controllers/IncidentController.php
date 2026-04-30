<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncidentController extends Controller
{
    public function create(): View
    {
        return view('incidents.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:technical,user'],
            'title' => ['required', 'string', 'min:4', 'max:150'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'type.required' => 'Selecciona un tipo de incidencia.',
            'type.in' => 'El tipo de incidencia seleccionado no es válido.',
            'title.required' => 'El título es obligatorio.',
            'title.min' => 'El título debe tener al menos 4 caracteres.',
            'description.required' => 'La descripción es obligatoria.',
            'description.min' => 'La descripción debe tener al menos 10 caracteres.',
        ]);

        Incident::create([
            'user_id' => $request->user()->id,
            'type' => $data['type'],
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => 'open',
            'created_at' => now(),
        ]);

        return redirect()
            ->route('incidents.create')
            ->with('success', 'Incidencia enviada correctamente.');
    }
}
