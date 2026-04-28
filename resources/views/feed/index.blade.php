@extends('layouts.app')

@section('page', 'feed')

@section('title', 'Klassify - Feed')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/index.css') }}">
@endsection

@section('content')
<div class="mobile-overlay"></div>
<main class="k-layout">
    <div class="app">
        <aside class="app-left">
            <div class="k-recursosDestacados-card">
                <h2>Recursos Destacados</h2>

                @include('feed.partials.featured-resources')
            </div>
        </aside>

        <section class="app-center">
            <div class="forYou-follow-section">
                <span class="tab-indicator" aria-hidden="true"></span>
                <div class="k-forYou tab-active" data-tab="for-you">
                    <h2>Para ti</h2>
                </div>
                <div class="k-follow" data-tab="follow">
                    <h2>Siguiendo</h2>
                </div>
            </div>
            <div class="filter-card">
                <div class="filter-container">
                    <select class="k-select" id="course-filter" name="course">
                        <option value="" selected disabled>Curso</option>
                        @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                    <select class="k-select" id="subject-filter" name="subject" disabled>
                        <option value="" selected disabled>Materia</option>
                    </select>

                    <div class="fileTypes-content">
                        <label for="imagen">Imagen</label>
                        <input type="checkbox" name="fileType" id="imagen">
                        <label for="documento">Documento</label>
                        <input type="checkbox" name="fileType" id="documento">
                        <label for="video">Video</label>
                        <input type="checkbox" name="fileType" id="video">
                        <label for="audio">Audio</label>
                        <input type="checkbox" name="fileType" id="audio">
                    </div>
                </div>
            </div>

            <script type="application/json" id="courses-with-subjects-data">
                @json($courses)
            </script>
            <script>
                window.coursesWithSubjects = JSON.parse(
                    document.getElementById('courses-with-subjects-data')?.textContent ?? '[]'
                );
            </script>

            @forelse ($resources as $resource)
            @include('feed.partials.resource-card', ['resource' => $resource])
            @empty
            <div class="recurs-card">
                <div class="recurs-content">
                    <h3 class="recurs-title">Todavía no hay recursos publicados</h3>
                    <p class="recurs-description">Cuando subas recursos reales, aparecerán aquí en esta misma estructura.</p>
                </div>
            </div>
            @endforelse

            @include('feed.partials.load-more')

            @include('feed.partials.resource-preview-modal')
        </section>

        <aside class="app-right">
            @include('feed.partials.sidebar')
        </aside>
    </div>
</main>
@endsection