<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceRequest;
use App\Models\Resource;
use App\Models\Course;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function entry(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $role = strtoupper((string) ($user->role ?? ''));
        $teacherStatus = strtoupper((string) ($user->teacher_status ?? ''));

        if ($role === 'ADMIN') {
            return redirect()->route('admin.resources.create');
        }

        if ($role === 'TEACHER') {
            if (in_array($teacherStatus, ['ACTIVE', 'VERIFIED'], true)) {
                return redirect()->route('teacher.resources.create');
            }

            return redirect()->route('teacher.pending')->with('error', 'Tu cuenta de profesor está pendiente de aprobación.');
        }

        return redirect()->route('feed')->with('error', 'No tienes permiso para publicar recursos.');
    }

    public function create(Request $request): View
    {
        $scope = $this->resolveScope($request);
        $courses = Course::query()
            ->with(['subjects' => function ($query) {
                $query->orderBy('name');
            }])
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('resources.create', [
            'scope' => $scope,
            'scopeLabel' => $scope === 'admin' ? 'Administrador' : 'Profesor verificado',
            'storeRoute' => route($scope . '.resources.store'),
            'courses' => $courses,
        ]);
    }

    public function store(StoreResourceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $file = $request->file('resource_file');

        if (!$file) {
            throw ValidationException::withMessages([
                'resource_file' => 'Debes seleccionar un archivo.',
            ]);
        }

        if (!$file->isValid()) {
            throw ValidationException::withMessages([
                'resource_file' => 'El tipo de archivo no está permitido.',
            ]);
        }

        $resourceType = $this->resolveResourceType(
            strtolower((string) $file->getClientOriginalExtension()),
            $request->boolean('is_exam')
        );

        if (!$resourceType) {
            throw ValidationException::withMessages([
                'resource_file' => 'El tipo de archivo no está permitido.',
            ]);
        }

        /** @var FilesystemAdapter $s3 */
        $s3 = Storage::disk('s3');

        $folder = 'resources/' . $userId;
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = $s3->putFileAs(
            $folder,
            $file,
            $fileName
        );

        if ($path === false) {
            return redirect()->back()->with('error', 'No se pudo subir el archivo. Inténtalo de nuevo.');
        }

        $fileUrl = $s3->url($path);

        Resource::create([
            'user_id' => $userId,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $resourceType,
            'course_id' => $validated['course_id'] ?? null,
            'subject_id' => $validated['subject_id'] ?? null,
            'file_url' => $fileUrl,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return redirect()
            ->route('feed')
            ->with('success', 'Recurso subido correctamente.');
    }

    private function resolveScope(Request $request): string
    {
        return $request->routeIs('admin.*') ? 'admin' : 'teacher';
    }

    private function resolveResourceType(string $extension, bool $isExam): ?string
    {
        if ($isExam) {
            return 'exam';
        }

        return match ($extension) {
            'pdf', 'doc', 'docx', 'ppt', 'pptx' => 'document',
            'mp4' => 'video',
            'mp3' => 'audio',
            'jpeg', 'png' => 'image',
            default => null,
        };
    }
}
