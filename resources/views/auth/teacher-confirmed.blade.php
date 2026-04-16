@extends('layouts.auth')

@section('title', 'Profesor validado')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/teacher-confirmed.css') }}">
@endsection


@section('content')
    <div class="k-card">
        <h2>Profesor validado correctamente</h2>
        <p>
            La institución ha confirmado la solicitud y la cuenta del profesor ya está activa.
        </p>
        <p> Ya puedes cerrar esta ventana. </p>
    </div>
@endsection