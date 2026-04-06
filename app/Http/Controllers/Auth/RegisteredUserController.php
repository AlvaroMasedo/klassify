<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        try {
            $courses = Course::orderBy('name')->get()->unique('name')->values();
        } catch (QueryException) {
            $courses = collect();
        }

        return view('auth.register', [
            'courses' => $courses,
            'preview' => session('register.preview', []),
        ]);
    }

    public function review(RegisterUserRequest $request): View
    {
        $data = $request->validated();
        $courseId = $request->input('course_id');
        $courseName = Course::query()->whereKey($courseId)->value('name');

        session([
            'register.preview' => [
                'name' => $data['name'],
                'surname' => $data['surname'],
                'nickname' => $data['nickname'],
                'email' => $data['email'],
                'role' => $data['role'],
                'course_id' => $courseId,
                'course_name' => $courseName,
                'name_institucion' => $data['name_institucion'] ?? null,
                'direccion' => $data['direccion'] ?? null,
                'email_institucional' => $data['email_institucional'] ?? null,
                'terms' => $data['terms'] ?? null,
                'specialization' => $courseName,
            ],
            'register.password' => Crypt::encryptString($data['password']),
        ]);

        return view('auth.register-review', [
            'data' => session('register.preview'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = session('register.preview');
        $encryptedPassword = session('register.password');

        if (!$data || !$encryptedPassword) {
            return redirect()
                ->route('register')
                ->withErrors(['register' => 'No hay datos pendientes de confirmación.']);
        }

        if (User::where('email', $data['email'])->exists()) {
            return redirect()
                ->route('register')
                ->withErrors(['email' => 'Este email ya está registrado.'])
                ->withInput();
        }

        if (User::where('nickname', $data['nickname'])->exists()) {
            return redirect()
                ->route('register')
                ->withErrors(['nickname' => 'Este nickname ya está en uso.'])
                ->withInput();
        }

        $teacherStatus = $data['role'] === 'TEACHER' ? 'PENDING' : 'ACTIVE';

        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'password' => Crypt::decryptString($encryptedPassword),
            'role' => $data['role'],
            'teacher_status' => $teacherStatus,
            'specialization' => $data['role'] === 'TEACHER' ? ($data['specialization'] ?? null) : null,
            'is_private' => false,
        ]);

        session()->forget(['register.preview', 'register.password']);

        Auth::login($user);

        return redirect()->route('feed');
    }
}