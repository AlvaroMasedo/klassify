@props([
    'user' => null,
    'alt' => 'Avatar',
])

@php
    $src = filled($user?->foto_perfil_url)
        ? $user->foto_perfil_url
        : asset('assets/img/default-profile-img.png');
@endphp

<img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => 'user-avatar']) }}>