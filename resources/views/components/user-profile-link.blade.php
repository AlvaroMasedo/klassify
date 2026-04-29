@props(['user' => null])

@php
    $nickname = trim((string) ($user?->nickname ?? ''));
@endphp

@if ($nickname !== '')
    <a
        href="{{ route('profile.show', ['user' => $nickname]) }}"
        {{ $attributes->merge(['class' => 'user-profile-link']) }}>
        {{ $slot }}
    </a>
@else
    <span {{ $attributes }}>
        {{ $slot }}
    </span>
@endif