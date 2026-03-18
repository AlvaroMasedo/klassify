<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Http\Requests\Auth\RegisterUserRequest;
use Illuminate\Support\Facades\Crypt;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', [
            'preview' => session('register.preview', []),
        ]);
    }

    public function review(\App\Http\Requests\Auth\RegisterUserRequest $request)
    {
        $data = $request->validated();

        session([
            'register.preview' => [
                'name' => $data['name'],
                'email' => $data['email'],
            ],
            'register.password' => Crypt::encryptString($data['password']),
        ]);

        return view('auth.register-review', [
            'data' => session('register.preview'),
        ]);
    }

    public function store(\App\Http\Requests\Auth\RegisterUserRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $teacherStatus = $validated['role'] === 'TEACHER' ? 'PENDING' : null;

            $user = User::create([
                'name' => $validated['name'],
                'surname' => $validated['surname'],
                'nickname' => $validated['nickname'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'teacher_status' => $teacherStatus,
                'specialization' => $validated['role'] === 'TEACHER' ? $validated['specialization'] : null,
                'is_private' => false,
            ]);

            if (!$user) {
                return redirect()->back()->with('error', 'Error al crear la cuenta. Intentalo de nuevo');
            }

            return redirect()->route('login')->with('success', 'Registro exitoso. Por favor, inicia sesión.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la cuenta: ' . $e->getMessage());
        }
    }
}
