<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

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
        $teacherRequest->update([
            'status' => 'APPROVED',
        ]);

        $teacherRequest->user()?->update([
            'teacher_status' => 'ACTIVE',
        ]);

        return redirect()
            ->route('admin.teacher-requests.index')
            ->with('success', 'La solicitud se ha aprobado correctamente.');
    }
}
