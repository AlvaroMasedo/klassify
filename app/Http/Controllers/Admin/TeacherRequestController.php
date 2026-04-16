<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TeacherInstitutionApprovalMail;
use App\Models\Institution;
use App\Models\TeacherRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TeacherRequestController extends Controller
{
    public function index(Request $request): View
    {
        $validStatuses = ['SUBMITTED', 'APPROVED', 'REJECTED'];
        $status = $request->query('status');

        $teacherRequests = TeacherRequest::with('user')
            ->when(
                $status && in_array($status, $validStatuses),
                fn ($query) => $query->where('status', $status)
            )
            ->latest('created_at')
            ->get();

        return view('admin.teacher-requests.index', [
            'teacherRequests' => $teacherRequests,
            'selectedStatus' => $status,
        ]);
    }

    public function approve(TeacherRequest $teacherRequest): RedirectResponse
    {
        if ($teacherRequest->status !== 'SUBMITTED') {
            return redirect()->route('admin.teacher-requests.index')
                ->with('error', 'Solo se pueden aprobar solicitudes enviadas.');
        }

        $institution = Institution::where('email', $teacherRequest->institution_email)
            ->orWhere('name', $teacherRequest->institution_name)
            ->first();

        if (!$institution) {
            $institution = Institution::create([
                'name' => $teacherRequest->institution_name,
                'email' => $teacherRequest->institution_email,
                'city' => $this->extractCityFromAddress($teacherRequest->address),
            ]);
        }

        $token = Str::random(64);

        $teacherRequest->update([
            'status' => 'APPROVED',
            'token' => $token,
        ]);

        $confirmationUrl = route('teacher-requests.confirm', $token);

        Mail::to($teacherRequest->institution_email)
            ->send(new TeacherInstitutionApprovalMail($teacherRequest->fresh('user'), $confirmationUrl));

        return redirect()->route('admin.teacher-requests.index')
            ->with('success', 'Solicitud aprobada y correo enviado a la institución.');
    }

    public function reject(TeacherRequest $teacherRequest): RedirectResponse
    {
        if ($teacherRequest->status !== 'SUBMITTED') {
            return redirect()->route('admin.teacher-requests.index')
                ->with('error', 'Solo se pueden rechazar solicitudes enviadas.');
        }

        $teacherRequest->update([
            'status' => 'REJECTED',
            'token' => null,
        ]);

        return redirect()->route('admin.teacher-requests.index')
            ->with('success', 'Solicitud rechazada correctamente.');
    }

    public function confirmByInstitution(string $token): View|RedirectResponse
    {
        $teacherRequest = TeacherRequest::with('user')
            ->where('token', $token)
            ->where('status', 'APPROVED')
            ->first();

        if (!$teacherRequest) {
            return redirect()->route('login')
                ->with('error', 'La solicitud no existe o ya ha sido confirmada.');
        }

        $institution = Institution::where('email', $teacherRequest->institution_email)
            ->orWhere('name', $teacherRequest->institution_name)
            ->first();

        if (!$institution) {
            return redirect()->route('login')
                ->with('error', 'No se ha encontrado la institución asociada.');
        }

        $teacherRequest->user->update([
            'teacher_status' => 'ACTIVE',
            'institution_id' => $institution->id,
        ]);

        $teacherRequest->update([
            'token' => null,
        ]);

        return view('auth.teacher-confirmed');
    }

    private function extractCityFromAddress(string $address): string
    {
        $parts = array_map('trim', explode(',', $address));

        return end($parts) ?: $address;
    }
}