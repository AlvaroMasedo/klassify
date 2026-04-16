<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\Institution;
use Illuminate\Support\Facades\Mail;

class TeacherRequestController extends Controller
{
    public function index(): View
    {
        $requests = TeacherRequest::with('user')
            ->whereIn('status', ['SUBMITTED', 'REJECTED', 'APPROVED'])
            ->latest('created_at')
            ->get();

        return view('admin.teacher-requests.index', compact('requests'));
    }

    public function approve(TeacherRequest $teacherRequest): RedirectResponse
    {
        $institution = Institution::create([
            'name' => $teacherRequest->institution_name,
            'email' => $teacherRequest->institution_email,
            'address' => $teacherRequest->address,
        ]);

        $token = Str::random(64);

        $teacherRequest->update([
            'status' => 'APPROVED',
            'token' => $token,
        ]);

        Mail::raw(
            "Se ha solicitado validar al profesor {$teacherRequest->user->name} {$teacherRequest->user->surname}. 
        Para aceptarlo, entra aquí: " . route('teacher-requests.institution-approve', $token),
            function ($message) use ($teacherRequest) {
                $message->to($teacherRequest->institution_email)
                    ->subject('Validación de profesor en Klassify');
            }
        );

        return redirect()->route('admin.teacher-requests.index')
            ->with('success', 'Solicitud aprobada y correo enviado a la institución.');
    }

    public function institutionApprove(string $token): RedirectResponse
    {
        $teacherRequest = TeacherRequest::with('user')
            ->where('token', $token)
            ->where('status', 'APPROVED')
            ->firstOrFail();

        $institution = Institution::where('email', $teacherRequest->institution_email)->first();

        if (!$institution) {
            return redirect()->route('home')->with('error', 'No se ha encontrado la institución.');
        }

        $teacherRequest->user->update([
            'teacher_status' => 'ACTIVE',
            'institution_id' => $institution->id,
        ]);

        $teacherRequest->update([
            'status' => 'APPROVED',
            'token' => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Profesor validado correctamente por la institución.');
    }
}
