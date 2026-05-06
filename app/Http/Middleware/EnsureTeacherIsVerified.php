<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacherIsVerified
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $role = strtoupper((string) ($user->role ?? ''));
        $teacherStatus = strtoupper((string) ($user->teacher_status ?? ''));

        if ($role !== 'TEACHER') {
            return redirect()->route('feed')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        if (!in_array($teacherStatus, ['ACTIVE', 'VERIFIED'], true)) {
            return redirect()->route('teacher.pending')
                ->with('error', 'Tu cuenta de profesor aún está pendiente de validación.');
        }
        return $next($request);
    }
}
