@extends('layouts.auth')

@section('title', 'Nueva contraseña')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/forgot-password.css') }}">
@endsection

@section('content')
<main>
    <div class="k-content k-forgot-content">
        <div class="k-content-title">
            <img class="k-logo" src="/assets/img/k-logo.png" alt="Logo de klassify">
            <span class="k-name">KLASSIFY</span>
        </div>

        <div class="k-content-body">
            <section class="k-forgot-card" aria-label="Formulario de nueva contraseña">
                <h2>Restablecer contraseña</h2>
                <p>Usa al menos 8 caracteres para mayor seguridad.</p>

                @if (session('status'))
                <div class="k-status-message" role="status">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <label for="email">Email
                        <input type="email" id="email" name="email" placeholder="Escribe tu email" autocomplete="email" value="{{ old('email', $email ?? '') }}" required>
                        @error('email')
                        <p class="p-error">{{ $message }}</p>
                        @enderror
                    </label>

                    <label for="password">Nueva contraseña
                        <input type="password" id="password" name="password" placeholder="Min. 8 caracteres, mayúscula, minúscula, número y símbolo" autocomplete="new-password" required>
                        @error('password')
                        <p class="p-error">{{ $message }}</p>
                        @enderror
                    </label>

                    <label for="password_confirmation">Confirmar contraseña
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repite la contraseña" autocomplete="new-password" required>
                    </label>

                    <div class="k-form-actions">
                        <button type="submit" class="k-submit-btn">Guardar nueva contraseña</button>
                        <p class="k-auth-switch">
                            <a class="k-auth-switch-link" href="{{ route('login') }}">Volver al inicio de sesion</a>
                        </p>
                    </div>
                </form>
            </section>
        </div>
    </div>
</main>
@endsection
