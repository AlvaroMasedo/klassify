@extends('layouts.app')

@section('title', 'Klassify - Feed')

@section('content')
<div class="k-layout">
    <aside class="k-col k-col--left">
        <h3 class="k-col__title">Destacados</h3>
        <div class="k-card">Recurso destacado #1</div>
        <div class="k-card">Recurso destacado #2</div>
    </aside>

    <section class="k-col k-col--center">
        <h2 class="k-col__title">Para ti</h2>
        <div class="k-card">Post/Resource #1</div>
        <div class="k-card">Post/Resource #2</div>
        <div class="k-card">Post/Resource #3</div>
    </section>
    
    <aside class="k-col k-col--right">
        <h3 class="k-col__title">Profes sugeridos</h3>
        <div class="k-card">Profe sugerido #1</div>
        <div class="k-card">Profe sugerido #2</div>
    </aside>
</div>
@endsection