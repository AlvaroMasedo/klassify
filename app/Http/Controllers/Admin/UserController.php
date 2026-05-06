<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'students');

        if (!in_array($filter, ['admins', 'students', 'teachers'], true)) {
            $filter = 'students';
        }

        $role = match ($filter) {
            'admins' => 'ADMIN',
            'teachers' => 'TEACHER',
            default => 'STUDENT',
        };

        $users = User::query()
            ->where('id', '!=', $request->user()->id)
            ->whereRaw('UPPER(role) = ?', [$role])
            ->orderBy('name')
            ->orderBy('surname')
            ->paginate(12)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'filter' => $filter,
        ]);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ((int) $user->id === (int) $request->user()->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $deletedUserEmail = (string) $user->email;
        $deletedUserName = trim(((string) $user->name) . ' ' . ((string) $user->surname));
        $deletedUserName = $deletedUserName !== '' ? $deletedUserName : ($user->nickname ?? 'usuario');

        DB::transaction(function () use ($user) {
            $userId = (int) $user->id;

            $resourceIds = Schema::hasTable('resources')
                ? DB::table('resources')->where('user_id', $userId)->pluck('id')->all()
                : [];

            $commentIds = Schema::hasTable('comments')
                ? DB::table('comments')->where('user_id', $userId)->pluck('id')->all()
                : [];

            if (!empty($resourceIds) && Schema::hasTable('comments')) {
                $commentIds = array_merge(
                    $commentIds,
                    DB::table('comments')->whereIn('resource_id', $resourceIds)->pluck('id')->all()
                );
            }

            $commentIds = array_values(array_unique(array_map('intval', $commentIds)));

            if (Schema::hasTable('reports')) {
                DB::table('reports')->where('reporter_id', $userId)->delete();

                if (!empty($resourceIds)) {
                    DB::table('reports')->whereIn('resource_id', $resourceIds)->delete();
                }

                if (!empty($commentIds) && Schema::hasColumn('reports', 'comment_id')) {
                    DB::table('reports')->whereIn('comment_id', $commentIds)->delete();
                }
            }

            if (Schema::hasTable('likes')) {
                DB::table('likes')->where('user_id', $userId)->delete();

                if (!empty($resourceIds)) {
                    DB::table('likes')->whereIn('resource_id', $resourceIds)->delete();
                }
            }

            if (Schema::hasTable('favorites')) {
                DB::table('favorites')->where('user_id', $userId)->delete();

                if (!empty($resourceIds)) {
                    DB::table('favorites')->whereIn('resource_id', $resourceIds)->delete();
                }
            }

            if (Schema::hasTable('follows')) {
                DB::table('follows')
                    ->where('follower_id', $userId)
                    ->orWhere('followed_id', $userId)
                    ->delete();
            }

            if (Schema::hasTable('comments')) {
                DB::table('comments')->where('user_id', $userId)->delete();

                if (!empty($resourceIds)) {
                    DB::table('comments')->whereIn('resource_id', $resourceIds)->delete();
                }
            }

            if (Schema::hasTable('incidents')) {
                DB::table('incidents')->where('user_id', $userId)->delete();
            }

            if (Schema::hasTable('teacher_requests')) {
                if (Schema::hasColumn('teacher_requests', 'user_id')) {
                    DB::table('teacher_requests')->where('user_id', $userId)->delete();
                }

                if (Schema::hasColumn('teacher_requests', 'teacher_id')) {
                    DB::table('teacher_requests')->where('teacher_id', $userId)->delete();
                }

                if (Schema::hasColumn('teacher_requests', 'email') && !empty($user->email)) {
                    DB::table('teacher_requests')->where('email', $user->email)->delete();
                }
            }

            if (!empty($resourceIds) && Schema::hasTable('resources')) {
                DB::table('resources')->whereIn('id', $resourceIds)->delete();
            }

            $user->delete();
        });

        if ($deletedUserEmail !== '') {
            try {
                Mail::raw(
                    "Hola {$deletedUserName},\n\n" .
                    "Te informamos de que tu cuenta de Klassify ha sido eliminada por un administrador.\n\n" .
                    "Esta decisión puede deberse al incumplimiento de las normas de la comunidad, uso inadecuado de la plataforma, publicación de contenido no permitido o comportamiento contrario a las condiciones de Klassify.\n\n" .
                    "Al eliminar la cuenta, también se han eliminado los recursos, comentarios y actividad asociados a ella.\n\n" .
                    "Si consideras que se trata de un error, ponte en contacto con nosotros desde los canales de soporte de Klassify.\n\n" .
                    "Atentamente,\n" .
                    "El equipo de Klassify",
                    function ($message) use ($deletedUserEmail) {
                        $message
                            ->to($deletedUserEmail)
                            ->subject('Tu cuenta de Klassify ha sido eliminada');
                    }
                );
            } catch (\Throwable $exception) {
                return redirect()
                    ->route('admin.users.index')
                    ->with('warning', 'Usuario eliminado, pero no se pudo enviar el correo informativo.');
            }
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario eliminado correctamente. Se ha enviado un correo informativo.');
    }
}