@extends('layouts.app')

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

                <!-- Recursos destacados -->
                <div class="rd-card">
                    <h3>Nom Recurs</h3>
                    <p class="p-pequeño">Tipo: Video</p>


                    <div class="k-interacts">
                        <!-- Icono Corazon -->
                        <div class="k-likes">
                            <svg class="icon-heart" data-favorite="false" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                                <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z" />
                            </svg>
                            <p class="p-numero-pequeño">123</p>
                        </div>

                        <!-- Icono Comentarios -->
                        <div class="k-comments">
                            <svg class="icon-comment" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                                <path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                            </svg>
                            <p class="p-numero-pequeño">45</p>
                        </div>
                    </div>


                </div>
                <!-- Recursos destacados -->
                <div class="rd-card">
                    <h3>Nom Recurs</h3>
                    <p class="p-pequeño">Tipo: Video</p>


                    <div class="k-interacts">
                        <!-- Icono Corazon -->
                        <div class="k-likes">
                            <svg class="icon-heart" data-favorite="false" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                                <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z" />
                            </svg>
                            <p class="p-numero-pequeño">123</p>
                        </div>

                        <!-- Icono Comentarios -->
                        <div class="k-comments">
                            <svg class="icon-comment" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                                <path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                            </svg>
                            <p class="p-numero-pequeño">45</p>
                        </div>
                    </div>


                </div>
                <!-- Recursos destacados -->
                <div class="rd-card">
                    <h3>Nom Recurs</h3>
                    <p class="p-pequeño">Tipo: Video</p>


                    <div class="k-interacts">
                        <!-- Icono Corazon -->
                        <div class="k-likes">
                            <svg class="icon-heart" data-favorite="false" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                                <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z" />
                            </svg>
                            <p class="p-numero-pequeño">123</p>
                        </div>

                        <!-- Icono Comentarios -->
                        <div class="k-comments">
                            <svg class="icon-comment" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                                <path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                            </svg>
                            <p class="p-numero-pequeño">45</p>
                        </div>
                    </div>


                </div>
                <!-- Recursos destacados -->
                <div class="rd-card">
                    <h3>Nom Recurs</h3>
                    <p class="p-pequeño">Tipo: Video</p>


                    <div class="k-interacts">
                        <!-- Icono Corazon -->
                        <div class="k-likes">
                            <svg class="icon-heart" data-favorite="false" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                                <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z" />
                            </svg>
                            <p class="p-numero-pequeño">123</p>
                        </div>

                        <!-- Icono Comentarios -->
                        <div class="k-comments">
                            <svg class="icon-comment" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                                <path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                            </svg>
                            <p class="p-numero-pequeño">45</p>
                        </div>
                    </div>


                </div>
                <!-- Recursos destacados -->
                <div class="rd-card">
                    <h3>Nom Recurs</h3>
                    <p class="p-pequeño">Tipo: Video</p>


                    <div class="k-interacts">
                        <!-- Icono Corazon -->
                        <div class="k-likes">
                            <svg class="icon-heart" data-favorite="false" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                                <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z" />
                            </svg>
                            <p class="p-numero-pequeño">123</p>
                        </div>

                        <!-- Icono Comentarios -->
                        <div class="k-comments">
                            <svg class="icon-comment" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#2d1b3d">
                                <path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                            </svg>
                            <p class="p-numero-pequeño">45</p>
                        </div>
                    </div>


                </div>
                <div class="view-more">
                    <p>Mostrar más</p>
                </div>
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
                        <option value="primaria">Primaria</option>
                        <option value="secundaria">Secundaria</option>
                        <option value="bachillerato">Bachillerato</option>
                        <option value="cfm-smr">CFM SMR</option>
                        <option value="cfs-asir">CFS ASIR</option>
                        <option value="cfs-daw">CFS DAW</option>
                        <option value="magisterio">Magisterio</option>
                        <option value="ingenieria-datos">Ingenieria de datos</option>
                        <option value="ingenieria-informatica">Ingenieria informatica</option>
                        <option value="ade">ADE</option>
                        <option value="derecho">Derecho</option>
                        <option value="medicina">Medicina</option>
                        <option value="psicologia">Psicologia</option>
                    </select>
                    <select class="k-select" id="subject-filter" name="subject">
                        <option value="" selected disabled>Materia</option>
                        <option value="matematicas">Matematicas</option>
                        <option value="lengua-castellana">Lengua castellana</option>
                        <option value="lengua-catalana">Lengua catalana</option>
                        <option value="ingles">Ingles</option>
                        <option value="historia">Historia</option>
                        <option value="geografia">Geografia</option>
                        <option value="biologia">Biologia</option>
                        <option value="fisica">Fisica</option>
                        <option value="quimica">Quimica</option>
                        <option value="programacion">Programacion</option>
                        <option value="bases-datos">Bases de datos</option>
                        <option value="sistemas">Sistemas informaticos</option>
                        <option value="redes">Redes</option>
                        <option value="fol">FOL</option>
                        <option value="empresa">Empresa e iniciativa</option>
                    </select>
                    <div class="fileTypes-content">
                        <label for="text">Texto</label>
                        <input type="checkbox" name="fileType" id="text">
                        <label for="documento">Documento</label>
                        <input type="checkbox" name="fileType" id="documento">
                        <label for="video">Video</label>
                        <input type="checkbox" name="fileType" id="video">
                        <label for="enlace">Enlace</label>
                        <input type="checkbox" name="fileType" id="enlace">
                    </div>
                </div>
            </div>

            <div class="recurs-card">
                <!-- Header del recurso -->
                <div class="recurs-header">
                    <div class="recurs-user-info">
                        <div class="recurs-avatar">
                            <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                        </div>
                        <div class="recurs-user-details">
                            <div class="recurs-name-container">
                                <span class="recurs-name">Nombre</span>
                                <span class="recurs-username">@nick-name</span>
                                <svg class="verified-badge" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1DA1F2">
                                    <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z" />
                                </svg>
                            </div>
                            <p class="recurs-meta">Curso: Bachillerato | Asignatura: España</p>
                        </div>
                    </div>
                    <button class="recurs-more-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2d1b3d">
                            <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z" />
                        </svg>
                    </button>
                </div>

                <!-- Contenido del recurso -->
                <div class="recurs-content">
                    <h3 class="recurs-title">La Guerra Civil Española (1936-1939) – Resumen en vídeo</h3>
                    <p class="recurs-description">Vídeo explicativo sobre las claves del desarrollo y las consecuencias de la Guerra Civil Española, pensado para alumnado de Bachillerato. El recurso incluye un repaso cronológico de los principales acontecimientos, así como de los conceptos clave necesarios para comprender el contexto histórico.</p>
                </div>

                <!-- Thumbnail del video -->
                <div class="recurs-media">
                    <div class="recurs-video-thumbnail">
                        <img src="{{ asset('assets/images/video-placeholder.jpg') }}" alt="Video thumbnail">
                        <div class="play-button-overlay">
                            <svg xmlns="http://www.w3.org/2000/svg" height="60px" viewBox="0 -960 960 960" width="60px" fill="#FFFFFF">
                                <path d="M320-200v-560l440 280-440 280Z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Interacciones del recurso -->
                <div class="recurs-interactions">
                    <div class="recurs-action">
                        <svg class="icon-heart" data-favorite="false" xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#2d1b3d">
                            <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z" />
                        </svg>
                        <span class="recurs-count">123</span>
                    </div>
                    <div class="recurs-action">
                        <svg class="icon-comment" xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#2d1b3d">
                            <path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                        </svg>
                        <span class="recurs-count">45</span>
                    </div>
                    <div class="recurs-action">
                        <svg class="icon-bookmark" data-saved="false" xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#2d1b3d">
                            <path d="M200-120v-640q0-33 23.5-56.5T280-840h400q33 0 56.5 23.5T760-760v640L480-240 200-120Zm80-122 200-86 200 86v-518H280v518Zm0-518h400-400Z" />
                        </svg>
                        <span class="recurs-count">12</span>
                    </div>
                </div>
            </div>
            <div class="recurs-card">
                <!-- Header del recurso -->
                <div class="recurs-header">
                    <div class="recurs-user-info">
                        <div class="recurs-avatar">
                            <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                        </div>
                        <div class="recurs-user-details">
                            <div class="recurs-name-container">
                                <span class="recurs-name">Nombre</span>
                                <span class="recurs-username">@nick-name</span>
                                <svg class="verified-badge" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1DA1F2">
                                    <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z" />
                                </svg>
                            </div>
                            <p class="recurs-meta">Curso: Bachillerato | Asignatura: España</p>
                        </div>
                    </div>
                    <button class="recurs-more-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#2d1b3d">
                            <path d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z" />
                        </svg>
                    </button>
                </div>

                <!-- Contenido del recurso -->
                <div class="recurs-content">
                    <h3 class="recurs-title">La Guerra Civil Española (1936-1939) – Resumen en vídeo</h3>
                    <p class="recurs-description">Vídeo explicativo sobre las claves del desarrollo y las consecuencias de la Guerra Civil Española, pensado para alumnado de Bachillerato. El recurso incluye un repaso cronológico de los principales acontecimientos, así como de los conceptos clave necesarios para comprender el contexto histórico.</p>
                </div>

                <!-- Thumbnail del video -->
                <div class="recurs-media">
                    <div class="recurs-video-thumbnail">
                        <img src="{{ asset('assets/images/video-placeholder.jpg') }}" alt="Video thumbnail">
                        <div class="play-button-overlay">
                            <svg xmlns="http://www.w3.org/2000/svg" height="60px" viewBox="0 -960 960 960" width="60px" fill="#FFFFFF">
                                <path d="M320-200v-560l440 280-440 280Z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Interacciones del recurso -->
                <div class="recurs-interactions">
                    <div class="recurs-action">
                        <svg class="icon-heart" data-favorite="false" xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#2d1b3d">
                            <path d="m480-120-58-52q-101-91-167-157T150-447.5Q111-500 95.5-544T80-634q0-94 63-157t157-63q52 0 99 22t81 62q34-40 81-62t99-22q94 0 157 63t63 157q0 46-15.5 90T810-447.5Q771-395 705-329T538-172l-58 52Zm0-108q96-86 158-147.5t98-107q36-45.5 50-81t14-70.5q0-60-40-100t-100-40q-47 0-87 26.5T518-680h-76q-15-41-55-67.5T300-774q-60 0-100 40t-40 100q0 35 14 70.5t50 81q36 45.5 98 107T480-228Zm0-273Z" />
                        </svg>
                        <span class="recurs-count">123</span>
                    </div>
                    <div class="recurs-action">
                        <svg class="icon-comment" xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#2d1b3d">
                            <path d="M80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z" />
                        </svg>
                        <span class="recurs-count">45</span>
                    </div>
                    <div class="recurs-action">
                        <svg class="icon-bookmark" data-saved="false" xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#2d1b3d">
                            <path d="M200-120v-640q0-33 23.5-56.5T280-840h400q33 0 56.5 23.5T760-760v640L480-240 200-120Zm80-122 200-86 200 86v-518H280v518Zm0-518h400-400Z" />
                        </svg>
                        <span class="recurs-count">12</span>
                    </div>
                </div>
            </div>
        </section>

        <aside class="app-right">
            <div class="k-recursosDestacados-card">
                <h2>Profesores Sugeridos</h2>

                <!-- Profesor sugerido -->
                <div class="teacher-card">
                    <div class="teacher-header">
                        <div class="teacher-user-info">
                            <div class="teacher-avatar">
                                <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                            </div>
                            <div class="teacher-user-details">
                                <div class="teacher-name-container">
                                    <span class="teacher-name">Nombre</span>
                                    <span class="teacher-username">@nick-name</span>
                                    <svg class="verified-badge" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1DA1F2">
                                        <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z" />
                                    </svg>
                                </div>
                                <p class="teacher-meta">Centro: Institut Sa Palomera </p>
                            </div>
                        </div>
                        <button class="teacher-follow-btn">
                            <span>Seguir</span>
                        </button>
                    </div>
                </div>

                <!-- Profesor sugerido -->
                <div class="teacher-card">
                    <div class="teacher-header">
                        <div class="teacher-user-info">
                            <div class="teacher-avatar">
                                <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                            </div>
                            <div class="teacher-user-details">
                                <div class="teacher-name-container">
                                    <span class="teacher-name">Nombre</span>
                                    <span class="teacher-username">@nick-name</span>
                                    <svg class="verified-badge" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1DA1F2">
                                        <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z" />
                                    </svg>
                                </div>
                                <p class="teacher-meta">Centro: Institut Sa Palomera </p>
                            </div>
                        </div>
                        <button class="teacher-follow-btn">
                            <span>Seguir</span>
                        </button>
                    </div>
                </div>

                <!-- Profesor sugerido -->
                <div class="teacher-card">
                    <div class="teacher-header">
                        <div class="teacher-user-info">
                            <div class="teacher-avatar">
                                <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                            </div>
                            <div class="teacher-user-details">
                                <div class="teacher-name-container">
                                    <span class="teacher-name">Nombre</span>
                                    <span class="teacher-username">@nick-name</span>
                                    <svg class="verified-badge" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1DA1F2">
                                        <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z" />
                                    </svg>
                                </div>
                                <p class="teacher-meta">Centro: Institut Sa Palomera </p>
                            </div>
                        </div>
                        <button class="teacher-follow-btn">
                            <span>Seguir</span>
                        </button>
                    </div>
                </div>

                <!-- Profesor sugerido -->
                <div class="teacher-card">
                    <div class="teacher-header">
                        <div class="teacher-user-info">
                            <div class="teacher-avatar">
                                <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                            </div>
                            <div class="teacher-user-details">
                                <div class="teacher-name-container">
                                    <span class="teacher-name">Nombre</span>
                                    <span class="teacher-username">@nick-name</span>
                                    <svg class="verified-badge" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1DA1F2">
                                        <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z" />
                                    </svg>
                                </div>
                                <p class="teacher-meta">Centro: Institut Sa Palomera </p>
                            </div>
                        </div>
                        <button class="teacher-follow-btn">
                            <span>Seguir</span>
                        </button>
                    </div>
                </div>

                <!-- Profesor sugerido -->
                <div class="teacher-card">
                    <div class="teacher-header">
                        <div class="teacher-user-info">
                            <div class="teacher-avatar">
                                <img src="{{ asset('assets/img/default-profile-img.png') }}" alt="Avatar">
                            </div>
                            <div class="teacher-user-details">
                                <div class="teacher-name-container">
                                    <span class="teacher-name">Nombre</span>
                                    <span class="teacher-username">@nick-name</span>
                                    <svg class="verified-badge" xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="#1DA1F2">
                                        <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z" />
                                    </svg>
                                </div>
                                <p class="teacher-meta">Centro: Institut Sa Palomera </p>
                            </div>
                        </div>
                        <button class="teacher-follow-btn">
                            <span>Seguir</span>
                        </button>
                    </div>
                </div>
                <div class="view-more">
                    <p>Mostrar más</p>
                </div>
            </div>
            <div class="sidebar-footer">
                <nav class="sidebar-footer-links">
                    <a href="#">Sobre Klassify</a>
                    <a href="#">Normas de la Comunidad</a>
                    <a href="#">Privacidad</a>
                    <a href="#">Ayuda</a>
                    <a href="#">Contacto</a>
                </nav>
                <div class="sidebar-footer-brand">
                    <img src="{{ asset('assets/img/k-logo.png') }}" alt="Logo de Klassify">
                    <span>© {{ date('Y') }} Klassify</span>
                </div>
            </div>
        </aside>
    </div>
</main>
@endsection