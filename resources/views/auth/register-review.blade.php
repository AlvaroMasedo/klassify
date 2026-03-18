@extends('layouts.auth')

@section('title', 'Registrarse')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/register.css') }}">
@endsection

@section('content')
<main>
    <div class="k-content">
        <h1>Confirma tus datos</h1>

        <div>
            <p><strong>Nombre:</strong> {{ $data['name'] }}</p>
            <p><strong>Apellido:</strong> {{ $data['surname'] }}</p>
            <p><strong>Nombre de usuario:</strong> {{ $data['nickname'] }}</p>
            <p><strong>Email:</strong> {{ $data['email'] }}</p>
            <p><strong>Contraseña:</strong> ********</p>
        </div>

        <div>
            <p><strong>Eres:</strong> {{ $data['role'] }}</p>
            @if ($data['role'] === 'TEACHER')
                <p><strong>Centro educativo:</strong> {{ $data['school'] }}</p>
                <p><strong>Ubicación:</strong> {{ $data['location'] }}</p>
                <p><strong>Especialización:</strong> {{ $data['specialization'] }}</p>
            @endif
        </div>

        <form method="POST" action="{{ route('register.confirm') }}">
            @csrf
            <button type="submit">Aceptar y registrarme</button>
        </form>

        <a href="{{ route('register') }}">Volver a editar</a>
    </div>
</main>
@endsection