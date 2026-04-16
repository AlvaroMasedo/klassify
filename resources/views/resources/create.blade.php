@extends('layouts.app')

@section('title', 'Subir recurso')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/resource-create.css') }}">
@endsection

@section('content')
<section class="resource-page">
    <div class="resource-card">
        <p class="resource-eyebrow">{{ $scopeLabel }}</p>
        <h1>Subir recurso</h1>
        <p class="resource-intro">Añade un archivo para compartirlo con la comunidad de Klassify.</p>

        @if (session('status'))
        <div class="resource-status" role="status">{{ session('status') }}</div>
        @endif

        @if (session('stored_resource'))
        <div class="resource-link-box">
            <span>Archivo guardado en:</span>
            <a href="{{ session('stored_resource') }}" target="_blank" rel="noreferrer">{{ session('stored_resource') }}</a>
        </div>
        @endif

        <form class="resource-form" method="POST" action="{{ $storeRoute }}" enctype="multipart/form-data">
            @csrf

            <label for="title">Título
                <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Escribe el título del recurso" required>
                @error('title')
                <p class="p-error">{{ $message }}</p>
                @enderror
            </label>

            <label for="description">Descripción
                <textarea id="description" name="description" rows="4" placeholder="Describe brevemente el recurso">{{ old('description') }}</textarea>
                @error('description')
                <p class="p-error">{{ $message }}</p>
                @enderror
            </label>

            <label for="resource_file">Archivo
                <input type="file" id="resource_file" name="resource_file" required>
                @error('resource_file')
                <p class="p-error">{{ $message }}</p>
                @enderror
            </label>

            <div class="resource-actions">
                <button type="submit" class="k-button">Publicar recurso</button>
                <a class="k-button-second" href="{{ route('feed') }}">Volver al feed</a>
            </div>
        </form>
    </div>
</section>
@endsection
