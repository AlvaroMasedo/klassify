@props(['user' => null])

@php
    $role = strtoupper((string) ($user?->role ?? ''));
    $teacherStatus = strtoupper((string) ($user?->teacher_status ?? ''));

    $isVerifiedUser = $role === 'ADMIN'
        || ($role === 'TEACHER' && in_array($teacherStatus, ['VERIFIED', 'ACTIVE'], true));
@endphp

@if ($isVerifiedUser)
    <svg
        class="verified-badge-icon"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 -960 960 960"
        aria-label="Usuario verificado"
        role="img">
        <path d="m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm94-278 226-226-56-58-170 170-86-84-56 56 142 142Z"/>
    </svg>
@endif