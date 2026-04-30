@extends('layouts.app')

@section('title', 'Ayuda')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/incidents.css') }}">
@endsection

@section('content')
<main class="incident-help-page">
    <section class="incident-help-card">
        <div class="incident-help-header">
            <span>Centro de ayuda</span>
            <h1>Enviar incidencia</h1>
            <p>
                Cuéntanos qué problema has encontrado en Klassify. Un administrador revisará tu incidencia.
            </p>
        </div>

        @if (session('success'))
        <div class="incident-help-success">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="incident-help-errors">
            <strong>Revisa los campos del formulario.</strong>

            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('incidents.store') }}" class="incident-help-form">
            @csrf

            <div class="incident-form-grid">
                <div class="incident-form-field">
                    <label for="type">Tipo de incidencia</label>
                    <select id="type" name="type" required>
                        <option value="">Selecciona una opción</option>
                        <option value="technical" @selected(old('type')==='technical' )>
                            Problema técnico
                        </option>
                        <option value="user" @selected(old('type')==='user' )>
                            Problema de usuario
                        </option>
                    </select>
                </div>

                <div class="incident-form-field">
                    <label for="title">Título</label>

                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        maxlength="150"
                        required
                        placeholder="Ej: No puedo abrir un recurso">
                </div>
            </div>

            <div class="incident-form-field">
                <label for="description">Descripción</label>

                <textarea
                    id="description"
                    name="description"
                    rows="7"
                    maxlength="2000"
                    required
                    placeholder="Explica qué ha pasado, en qué página ocurrió y cualquier detalle útil...">{{ old('description') }}</textarea>
            </div>

            <div class="incident-help-actions">
                <a href="{{ route('feed') }}" class="incident-help-cancel">
                    Volver al feed
                </a>

                <button type="submit" class="incident-help-submit">
                    Enviar incidencia
                </button>
            </div>
        </form>
    </section>
</main>
@endsection