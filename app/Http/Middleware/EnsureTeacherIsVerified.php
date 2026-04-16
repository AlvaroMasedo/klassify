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

        if (!$user || $user->teacher_status !== 'ACTIVE') {
            return redirect()->route('teacher.pending')->with('error', 'Tu cuenta de profesor está pendiente de aprobación. Por favor, espera a que un administrador revise tu solicitud.');
        }
        return $next($request);
    }
}
