@extends('layouts.auth')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher_pending.css') }}">
@endsection

@section('content')
<div class="k-card">
    <h2>Cuenta en revisión</h2>

    <p>
        Tu cuenta ha sido creada como profesor, pero está pendiente de validación.
    </p>

    <p>
        Hasta que no se verifique:
    </p>

    <ul>
        <li>No podrás subir recursos</li>
        <li>No podrás visualizar exámenes</li>
    </ul>

    <p>
        Te avisaremos cuando tu cuenta sea validada.
    </p>

    <a href="{{ route('home') }}" class="k-button">Ir al inicio</a>
</div>
@endsection