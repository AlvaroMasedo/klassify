@extends('layouts.auth')

@section('title', 'Registrarse')

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/register.css') }}">
@endsection

@section('content')
<main>
    <div class="k-content">
        <div class="k-content-title">
            <img class="k-logo" src="/assets/img/k-logo.png" alt="Logo de klassify">
            <span class="k-name">KLASSIFY</span>
        </div>
        <div class="k-content-body">
            <div class="k-authGirl-content">
                <img src="/assets/img/auth-girl.png" alt="girl-img">
            </div>
            <div class="k-registerForm-content">
                <h2>Crea tu cuenta</h2>
                <p>Accede a recursos educativos compartidos por docentes</p>
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="k-form-grid">
                        <label for="nombre">Nombre
                            <input type="text" id="nombre" name="nombre" placeholder="nombre" autofocus>
                            @error('name')
                            <p class="p-error">{{ $message }}</p>
                            @enderror
                        </label>

                        <label for="apellido">Apellido
                            <input type="text" id="apellido" name="apellido" placeholder="apellido">
                            @error('surname')
                            <p class="p-error">{{ $message }}</p>
                            @enderror
                        </label>
                    </div>

                    <label for="nickname">Nickname
                        <input type="text" id="nickname" name="nickname" placeholder="nickname">
                        @error('nickname')
                        <p class="p-error">{{ $message }}</p>
                        @enderror
                    </label>

                    <label for="email">Email
                        <input type="text" id="email" name="email" placeholder="email@ejemplo.com">
                        @error('email')
                        <p class="p-error">{{ $message }}</p>
                        @enderror
                    </label>

                    <label for="contraseña">Contraseña
                        <input type="password" id="contraseña" name="contraseña" placeholder="contraseña">
                        @foreach ($errors->get('password') as $message)
                        <p class="p-error">{{ $message }}</p>
                        @endforeach
                    </label>

                    <label for="confirmar-contraseña">Confirmar Contraseña
                        <input type="password" id="confirmar-contraseña" name="confirmar-contraseña" placeholder="confirmar contraseña">
                    </label>

                    <div class="k-role-selector">
                        <p class="k-role-label">Selecciona</p>

                        <div class="k-role-toggle" id="role-toggle">
                            <span class="k-role-indicator" aria-hidden="true"></span>
                            <button type="button" class="k-role-option is-active" data-role="TEACHER" aria-pressed="true">
                                <span class="k-role-option-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" focusable="false">
                                        <path d="M12 3 1.5 8.25 12 13.5l8.59-4.3V16H22V8.25L12 3Zm-6.73 8.57V15c0 2.58 3.04 4.5 6.73 4.5s6.73-1.92 6.73-4.5v-3.43L12 15l-6.73-3.43Z" />
                                    </svg>
                                </span>
                                <span class="k-role-option-text">Profesor</span>
                            </button>

                            <button type="button" class="k-role-option" data-role="STUDENT" aria-pressed="false">
                                <span class="k-role-option-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" focusable="false">
                                        <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm0 2c-4.41 0-8 2.24-8 5v1h16v-1c0-2.76-3.59-5-8-5Z" />
                                    </svg>
                                </span>
                                <span class="k-role-option-text">Estudiante</span>
                            </button>
                        </div>

                        <input type="hidden" name="role" id="role-input" value="TEACHER">
                        <div id="teacher-fields">
                            <div class="k-form-group">
                                <label for="course_id">Curso</label>
                                <select name="course_id" id="course_id">
                                    <option value="">-- Selecciona un curso --</option>
                                    @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                                @error('specialization')
                                    <p class="p-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="k-form-group">
                                <h3 for="institucion_id">Institución donde enseñas</h3>
                                <label for="nombre_institucion">Nombre</label>
                                <input type="text" name="nombre_institucion" id="nombre_institucion" placeholder="nombre de la institución">

                                <label for="direccion">Dirección</label>
                                <input type="text" name="direccion" id="direccion" placeholder="dirección de la institución">

                                <label for="email_institucional">Email institucional</label>
                                <input type="email" name="email_institucional" id="email_institucional" placeholder="email institucional">
                            </div>
                        </div>

                        <div class="k-terminosUso">
                            <h4>Términos de uso</h4>
                            <div class="k-terminos-content">
                                <p>
                                    Bienvenido a Klassify. Al registrarte aceptas utilizar la plataforma
                                    únicamente con fines educativos y respetar las normas de la comunidad.
                                </p>

                                <p>
                                    Los usuarios deben compartir contenido educativo propio o con permiso
                                    de uso. No está permitido publicar contenido ofensivo, ilegal o que
                                    vulnere derechos de autor.
                                </p>

                                <p>
                                    Los profesores deberán ser verificados antes de poder publicar recursos.
                                    Klassify se reserva el derecho de suspender cuentas que incumplan las
                                    normas de la plataforma.
                                </p>

                                <p>
                                    Al continuar aceptas nuestras condiciones de uso y política de privacidad.
                                </p>
                            </div>
                            <label class="k-terms-checkbox">
                                <input type="checkbox" name="terms" id="terms">
                                Acepto los términos de uso
                            </label>
                            @error('terms')
                                <p class="p-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="k-form-actions">
                            <button type="submit" class="k-submit-btn">Registrarse</button>
                            <p class="k-auth-switch">¿Ya tienes una cuenta? <a class="k-auth-switch-link" href="{{ route('login') }}">Inicia sesión</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- fi k-content-body -->
    </div>
</main>
@endsection