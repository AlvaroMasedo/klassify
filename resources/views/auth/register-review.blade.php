@extends('layouts.auth')

@section('title', 'Registrarse')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/register_review.css') }}">
@endsection

@section('content')
<main class="register-review-page">
    <div class="review-wrapper">
        <div class="review-header">
            <h1 class="review-title">Confirma tus datos</h1>
            <p class="review-subtitle">Revisa la información antes de finalizar el registro.</p>
        </div>

        <div class="review-card">
            <div class="review-card__section">
                <div class="review-grid">
                    <div class="review-item">
                        <span class="review-item__label">Nombre</span>
                        <span class="review-item__value">{{ $data['name'] }}</span>
                    </div>
                    <div class="review-item">
                        <span class="review-item__label">Apellido</span>
                        <span class="review-item__value">{{ $data['surname'] }}</span>
                    </div>
                    <div class="review-item">
                        <span class="review-item__label">Nombre de usuario</span>
                        <span class="review-item__value">{{ $data['nickname'] }}</span>
                    </div>
                    <div class="review-item">
                        <span class="review-item__label">Email</span>
                        <span class="review-item__value">{{ $data['email'] }}</span>
                    </div>
                    <div class="review-item review-item--full">
                        <span class="review-item__label">Contraseña</span>
                        <span class="review-item__value">********</span>
                    </div>
                    <div class="review-item review-item--full">
                        <span class="review-item__label">Rol</span>
                        <span class="review-item__value">{{ $data['role'] }}</span>
                    </div>
                    @if ($data['role'] === 'TEACHER')
                        <div class="review-item">
                            <span class="review-item__label">Curso</span>
                            <span class="review-item__value">{{ $data['course_name'] ?? $data['course_id'] }}</span>
                        </div>
                        <div class="review-item">
                            <span class="review-item__label">Centro educativo</span>
                            <span class="review-item__value">{{ $data['name_institucion'] }}</span>
                        </div>
                        <div class="review-item">
                            <span class="review-item__label">Ubicación</span>
                            <span class="review-item__value">{{ $data['direccion'] }}</span>
                        </div>
                        <div class="review-item">
                            <span class="review-item__label">Email institucional</span>
                            <span class="review-item__value">{{ $data['email_institucional'] }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="review-actions">
                <a class="btn btn-secondary" href="{{ route('register') }}">Volver a editar</a>

                <form method="POST" action="{{ route('register.confirm') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Aceptar y registrarme</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection