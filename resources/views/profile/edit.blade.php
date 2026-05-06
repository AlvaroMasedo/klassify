@extends('layouts.app')

@section('title', 'Editar perfil | Klassify')
@section('page', 'profile')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/profile.css') }}">
@endsection

@section('content')
@php
    $displayName = trim(((string) $profileUser->name) . ' ' . ((string) $profileUser->surname)) ?: 'Usuario';
@endphp

<main class="profile-edit-page">
    <section class="profile-edit-card">
        <header class="profile-edit-header">
            <div>
                <span>Perfil</span>
                <h1>Editar perfil</h1>
                <p>Actualiza tu nombre de usuario, privacidad, descripción, especialización, contraseña y foto.</p>
            </div>

            <a href="{{ route('profile.show', ['user' => $profileUser->nickname]) }}" class="profile-edit-back">
                Volver al perfil
            </a>
        </header>

        @if ($errors->any())
            <div class="profile-edit-errors">
                <strong>Revisa los campos del formulario.</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('profile.update', ['user' => $profileUser->nickname]) }}"
            enctype="multipart/form-data"
            class="profile-edit-form">
            @csrf
            @method('PUT')

            <section class="profile-edit-section">
                <h2>Información pública</h2>

                <div class="profile-edit-avatar-row">
                    <div class="profile-edit-avatar">
                        <x-user-avatar :user="$profileUser" alt="Avatar de {{ $displayName }}" />
                    </div>

                    <div class="profile-edit-field">
                        <label for="foto_perfil">Foto de perfil</label>
                        <input id="foto_perfil" type="file" name="foto_perfil" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                        <small>Solo JPG, JPEG o PNG. Máximo 2 MB.</small>

                        @error('foto_perfil')
                            <p class="profile-edit-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="profile-edit-grid">
                    <div class="profile-edit-field">
                        <label for="nickname">Nickname</label>
                        <input
                            id="nickname"
                            type="text"
                            name="nickname"
                            value="{{ old('nickname', $profileUser->nickname) }}"
                            required
                            maxlength="60">

                        @error('nickname')
                            <p class="profile-edit-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="profile-edit-field">
                        <label for="specialization">Especialización</label>
                        <select id="specialization" name="specialization">
                            <option value="">Sin especialización</option>

                            @foreach ($courses as $course)
                                <option
                                    value="{{ $course->name }}"
                                    @selected(old('specialization', $profileUser->specialization) === $course->name)>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('specialization')
                            <p class="profile-edit-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="profile-edit-field">
                    <label for="description">Descripción personalizada</label>
                    <textarea
                        id="description"
                        name="description"
                        maxlength="500"
                        rows="5"
                        placeholder="Cuenta algo sobre ti...">{{ old('description', $profileUser->description) }}</textarea>

                    @error('description')
                        <p class="profile-edit-error">{{ $message }}</p>
                    @enderror
                </div>

                <label class="profile-private-toggle">
                    <input
                        type="checkbox"
                        name="is_private"
                        value="1"
                        @checked(old('is_private', $profileUser->is_private))>

                    <span class="profile-private-slider"></span>

                    <strong>Perfil privado</strong>
                    <small>Si lo activas, otros usuarios no podrán ver tus recursos, seguidores ni seguirte.</small>
                </label>
            </section>

            <section class="profile-edit-section">
                <h2>Cambiar contraseña</h2>
                <p class="profile-edit-help">Rellena estos campos solo si quieres cambiar tu contraseña.</p>

                <div class="profile-edit-grid">
                    <div class="profile-edit-field">
                        <label for="current_password">Contraseña actual</label>
                        <input id="current_password" type="password" name="current_password" autocomplete="current-password">

                        @error('current_password')
                            <p class="profile-edit-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="profile-edit-field">
                        <label for="password">Nueva contraseña</label>
                        <input id="password" type="password" name="password" autocomplete="new-password">

                        @error('password')
                            <p class="profile-edit-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="profile-edit-field">
                        <label for="password_confirmation">Repetir nueva contraseña</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">

                        @error('password_confirmation')
                            <p class="profile-edit-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <div class="profile-edit-actions">
                <a href="{{ route('profile.show', ['user' => $profileUser->nickname]) }}" class="profile-edit-cancel">
                    Cancelar
                </a>

                <button type="submit" class="profile-edit-submit">
                    Guardar cambios
                </button>
            </div>
        </form>
    </section>
</main>
@endsection