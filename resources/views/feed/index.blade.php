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
            @php
            $activeFeedTab = $activeTab ?? 'for-you';
            @endphp

            <div class="forYou-follow-section {{ $activeFeedTab === 'following' ? 'is-follow' : '' }}">
                <span class="tab-indicator" aria-hidden="true"></span>

                <a
                    href="{{ route('feed') }}"
                    class="k-forYou {{ $activeFeedTab === 'for-you' ? 'tab-active' : '' }}"
                    data-tab="for-you">
                    <h2>Para ti</h2>
                </a>

                <a
                    href="{{ route('feed', ['tab' => 'following']) }}"
                    class="k-follow {{ $activeFeedTab === 'following' ? 'tab-active' : '' }}"
                    data-tab="following">
                    <h2>Siguiendo</h2>
                </a>
            </div>

            <div
                class="filter-card"
                data-feed-filters
                data-filter-url="{{ route('feed.resources') }}"
                data-active-tab="{{ $activeFeedTab }}">
                <div class="filter-container">
                    <select class="k-select" id="course-filter" name="course" data-filter-course>
                        <option value="" selected>Curso</option>

                        @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>

                    <select class="k-select" id="subject-filter" name="subject" data-filter-subject disabled>
                        <option value="" selected>Materia</option>
                    </select>

                    <div class="fileTypes-content">
                        <label for="imagen">Imagen</label>
                        <input type="checkbox" name="fileType" id="imagen" value="image" data-filter-type>

                        <label for="documento">Documento</label>
                        <input type="checkbox" name="fileType" id="documento" value="document" data-filter-type>

                        <label for="video">Video</label>
                        <input type="checkbox" name="fileType" id="video" value="video" data-filter-type>

                        <label for="audio">Audio</label>
                        <input type="checkbox" name="fileType" id="audio" value="audio" data-filter-type>
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

            <div data-feed-results>
                @forelse ($resources as $resource)
                @include('feed.partials.resource-card', ['resource' => $resource])
                @empty
                <div class="recurs-card">
                    <div class="recurs-content">
                        <h3 class="recurs-title">Todavía no hay recursos publicados</h3>
                        <p class="recurs-description">
                            Cuando subas recursos reales, aparecerán aquí.
                        </p>
                    </div>
                </div>
                @endforelse
            </div>

            <div data-feed-load-more-area>
                @include('feed.partials.load-more')
            </div>

            @include('feed.partials.resource-preview-modal')
        </section>

        <aside class="app-right">
            @include('feed.partials.sidebar')
        </aside>
    </div>
</main>

@include('feed.partials.favorite-toast')
@endsection