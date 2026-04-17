@extends('layouts.app')

@section('title', 'Subir recurso')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/resource-create.css') }}">
@endsection

@section('content')
<section class="resource-page">
    <div class="resource-card">
        <a class="resource-back-link" href="{{ route('feed') }}">&larr; Volver atrás</a>

        <header class="resource-hero">
            <div>
                <h1>Subir recurso</h1>
                <p class="resource-intro">Comparte materiales y ayuda a otros profesores y alumnos.</p>
            </div>
        </header>

        <hr class="resource-divider">

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

            <label for="title" class="resource-field-label">Título del recurso
                <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Escribe el título del recurso" required>
                @error('title')
                <p class="p-error">{{ $message }}</p>
                @enderror
            </label>

            <div class="resource-meta-grid">
                <label for="course_id" class="resource-field-label">Curso
                    <select id="course_id" name="course_id">
                        <option value="">Selecciona un curso</option>
                        @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected((string) old('course_id')===(string) $course->id)>
                            {{ $course->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('course_id')
                    <p class="p-error">{{ $message }}</p>
                    @enderror
                </label>

                <label for="subject_id" class="resource-field-label">Asignatura
                    <select id="subject_id" name="subject_id">
                        <option value="">Selecciona una asignatura</option>
                        @foreach ($courses as $course)
                        @foreach ($course->subjects as $subject)
                        <option value="{{ $subject->id }}" data-course-id="{{ $course->id }}" @selected((string) old('subject_id')===(string) $subject->id)>
                            {{ $subject->name }}
                        </option>
                        @endforeach
                        @endforeach
                    </select>
                    @error('subject_id')
                    <p class="p-error">{{ $message }}</p>
                    @enderror
                </label>
            </div>

            <label class="resource-toggle" for="is_exam">
                <span class="resource-toggle__text">¿Este recurso es un examen?</span>
                <input type="checkbox" id="is_exam" name="is_exam" value="1" @checked(old('is_exam'))>
                <span class="resource-toggle__track" aria-hidden="true">
                    <span class="resource-toggle__thumb"></span>
                </span>
            </label>

            <label for="description" class="resource-field-label">Descripción
                <textarea id="description" name="description" rows="4" placeholder="Añade una descripción del recurso, objetivos, contenidos, indicaciones de uso...">{{ old('description') }}</textarea>
                <span class="resource-counter"><span id="description_count">{{ mb_strlen((string) old('description', '')) }}</span> / 1000</span>
                @error('description')
                <p class="p-error">{{ $message }}</p>
                @enderror
            </label>

            <label for="resource_file" class="resource-upload-label">Archivo</label>
            <div class="resource-upload-box">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="108.1973 137.438 38.9247 52.485" width="38.9247px" height="52.485px" xmlns:bx="https://boxy-svg.com">
                    <rect x="114.523" y="137.438" width="21.396" height="52.485" style="stroke: rgb(0, 0, 0); stroke-width: 0px; fill: rgb(211, 195, 238);" transform="matrix(1, 0, 0, 1, -8.881784197001252e-16, 0)" />
                    <rect x="125.348" y="148.326" width="21.396" height="40.906" style="stroke: rgb(0, 0, 0); stroke-width: 0; fill: rgb(211, 195, 238);" transform="matrix(1, 0, 0, 1, -8.881784197001252e-16, 0)" />
                    <path d="M 135.794 138.319 L 147.122 149.395 L 135.794 149.395 L 135.794 138.319 Z" bx:shape="triangle 135.794 138.319 11.328 11.076 0 0 1@d0f78eac" style="stroke: rgb(81, 61, 116); stroke-width: 2; stroke-linejoin: round; stroke-linecap: round; fill: rgb(211, 195, 238); stroke-opacity: 0;" transform="matrix(1, 0, 0, 1, -8.881784197001252e-16, 0)" />
                    <path style="fill: none; stroke: rgb(81, 61, 116); stroke-width: 2px; stroke-linejoin: round; stroke-linecap: round;" d="M 114.523 141.467 L 114.523 137.439 L 135.291 137.565 L 147.122 148.893 L 146.744 189.673 C 146.744 189.673 114.649 189.672 114.649 189.547 C 114.649 189.422 114.649 173.688 114.649 173.688" transform="matrix(1, 0, 0, 1, -8.881784197001252e-16, 0)" />
                    <path d="M 135.794 137.817 L 147.122 148.893 L 135.794 148.893 L 135.794 137.817 Z" bx:shape="triangle 135.794 137.817 11.328 11.076 0 0 1@cc850316" style="fill: rgb(216, 216, 216); stroke: rgb(81, 61, 116); fill-opacity: 0; stroke-width: 2px; stroke-linejoin: round; stroke-linecap: round;" transform="matrix(1, 0, 0, 1, -8.881784197001252e-16, 0)" />
                    <path d="M 120.796 163.299 C 120.796 165.969 120.246 168.244 119.145 170.118 C 118.047 171.995 116.713 172.933 115.146 172.933 C 113.579 172.933 112.246 171.995 111.146 170.118 C 110.048 168.244 109.497 165.969 109.497 163.299 L 109.497 149.038 C 109.497 147.11 109.892 145.472 110.683 144.123 C 111.475 142.774 112.434 142.101 113.564 142.101 C 114.694 142.101 115.655 142.774 116.446 144.123 C 117.237 145.472 117.632 147.11 117.632 149.038 L 117.632 162.527 C 117.632 163.709 117.391 164.71 116.908 165.533 C 116.426 166.356 115.839 166.767 115.146 166.767 C 114.454 166.767 113.865 166.356 113.384 165.533 C 112.901 164.71 112.66 163.709 112.66 162.527 L 112.66 148.266 L 114.468 148.266 L 114.468 162.527 C 114.468 162.861 114.532 163.137 114.66 163.357 C 114.788 163.574 114.95 163.684 115.146 163.684 C 115.342 163.684 115.504 163.574 115.632 163.357 C 115.76 163.137 115.824 162.861 115.824 162.527 L 115.824 149.038 C 115.81 147.958 115.587 147.046 115.158 146.301 C 114.728 145.556 114.198 145.183 113.564 145.183 C 112.932 145.183 112.398 145.556 111.961 146.301 C 111.523 147.046 111.304 147.958 111.304 149.038 L 111.304 163.299 C 111.29 165.121 111.659 166.671 112.412 167.942 C 113.165 169.214 114.076 169.85 115.146 169.85 C 116.201 169.85 117.097 169.214 117.835 167.942 C 118.573 166.671 118.957 165.121 118.987 163.299 L 118.987 148.266 L 120.796 148.266 L 120.796 163.299 Z" style="stroke: rgb(81, 61, 116); fill: rgb(81, 61, 116); stroke-width: 0px; transform-origin: 115.147px 157.517px;" transform="matrix(0.99632502, 0.08565402, -0.08565402, 0.99632502, -8.2e-7, 0.00000126)" />
                </svg>

                <div class="resource-upload-content">
                    <p class="resource-upload-title">Selecciona un archivo</p>
                    <p class="resource-upload-help">Formatos admitidos: PDF, DOC, DOCX, PPT, PPTX, MP4, MP3, PNG, JPEG. Tamaño máximo: 80 MB.</p>

                    <div class="resource-upload-controls">
                        <input type="file" id="resource_file" name="resource_file" class="resource-upload-input" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.mp3,.png,.jpeg" required>
                        <label for="resource_file" class="resource-upload-button">Seleccionar archivo</label>
                        <span class="resource-upload-filename" id="resource_file_name">Ningún archivo seleccionado</span>
                    </div>
                </div>
            </div>

            @error('resource_file')
            <p class="p-error">{{ $message }}</p>
            @enderror

            <div class="resource-actions">
                <a class="resource-btn resource-btn--ghost" href="{{ route('feed') }}">&larr; Volver atrás</a>
                <button type="submit" class="resource-btn resource-btn--primary">Subir</button>
            </div>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.getElementById('resource_file');
        var fileName = document.getElementById('resource_file_name');
        var description = document.getElementById('description');
        var descriptionCount = document.getElementById('description_count');
        var courseSelect = document.getElementById('course_id');
        var subjectSelect = document.getElementById('subject_id');

        if (!input || !fileName) {
            return;
        }

        input.addEventListener('change', function() {
            if (input.files && input.files.length > 0) {
                fileName.textContent = input.files[0].name;
            } else {
                fileName.textContent = 'Ningún archivo seleccionado';
            }
        });

        if (description && descriptionCount) {
            var updateCounter = function() {
                descriptionCount.textContent = String(description.value.length);
            };

            description.addEventListener('input', updateCounter);
            updateCounter();
        }

        if (courseSelect && subjectSelect) {
            var subjectOptions = Array.prototype.slice.call(subjectSelect.querySelectorAll('option[data-course-id]'));

            var filterSubjects = function() {
                var selectedCourse = courseSelect.value;
                var previousValue = subjectSelect.value;
                var hasVisibleSelected = false;

                subjectOptions.forEach(function(option) {
                    var isVisible = selectedCourse !== '' && option.dataset.courseId === selectedCourse;
                    option.hidden = !isVisible;

                    if (isVisible && option.value === previousValue) {
                        hasVisibleSelected = true;
                    }
                });

                if (!hasVisibleSelected) {
                    subjectSelect.value = '';
                }
            };

            courseSelect.addEventListener('change', filterSubjects);
            filterSubjects();
        }
    });
</script>
@endsection