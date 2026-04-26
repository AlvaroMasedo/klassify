<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
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
use Symfony\Component\HttpFoundation\Response;

class ResourceController extends Controller
{
    public function preview(Request $request, Resource $resource): Response
    {
        $fileUrl = (string) ($resource->file_url ?? '');

        if ($fileUrl === '') {
            abort(404);
        }

        $path = parse_url($fileUrl, PHP_URL_PATH);

        if (!is_string($path) || $path === '') {
            abort(404);
        }

        $normalizedPath = ltrim($path, '/');
        $bucket = (string) config('filesystems.disks.s3.bucket');

        if ($bucket !== '' && str_starts_with($normalizedPath, $bucket . '/')) {
            $normalizedPath = substr($normalizedPath, strlen($bucket) + 1);
        }

        /** @var FilesystemAdapter $s3 */
        $s3 = Storage::disk('s3');

        return $s3->response($normalizedPath, $resource->file_name ?: null, [
            'Content-Disposition' => 'inline',
        ]);
    }

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
            'formAction' => route($scope . '.resources.store'),
            'formMethod' => 'POST',
            'pageTitle' => 'Subir recurso',
            'pageIntro' => 'Comparte materiales y ayuda a otros profesores y alumnos.',
            'submitLabel' => 'Subir',
            'isEdit' => false,
            'resource' => null,
            'courses' => $courses,
        ]);
    }

    public function edit(Request $request, Resource $resource): View
    {
        if ($request->user()?->id !== $resource->user_id) {
            abort(403);
        }

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
            'formAction' => route('resources.update', $resource),
            'formMethod' => 'PUT',
            'pageTitle' => 'Modificar recurso',
            'pageIntro' => 'Actualiza los datos y, si quieres, reemplaza el archivo.',
            'submitLabel' => 'Guardar cambios',
            'isEdit' => true,
            'resource' => $resource,
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

    public function update(UpdateResourceRequest $request, Resource $resource): RedirectResponse
    {
        if ($request->user()?->id !== $resource->user_id) {
            abort(403);
        }

        $validated = $request->validated();
        $file = $request->file('resource_file');
        $currentExtension = strtolower(pathinfo((string) ($resource->file_name ?? ''), PATHINFO_EXTENSION));
        $nextExtension = $file ? strtolower((string) $file->getClientOriginalExtension()) : $currentExtension;
        $resourceType = $this->resolveResourceType($nextExtension, $request->boolean('is_exam'));

        if (!$resourceType) {
            return redirect()->back()->withErrors([
                'resource_file' => 'El tipo de archivo no está permitido.',
            ])->withInput();
        }

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $resourceType,
            'course_id' => $validated['course_id'],
            'subject_id' => $validated['subject_id'],
            'mime_type' => $file ? $file->getMimeType() : $resource->mime_type,
        ];

        if ($file) {
            /** @var FilesystemAdapter $s3 */
            $s3 = Storage::disk('s3');

            $folder = 'resources/' . (string) $resource->user_id;
            $fileName = Str::uuid() . '.' . $nextExtension;
            $path = $s3->putFileAs($folder, $file, $fileName);

            if ($path === false) {
                return redirect()->back()->with('error', 'No se pudo subir el archivo. Inténtalo de nuevo.');
            }

            $data['file_url'] = $s3->url($path);
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();

            $this->deleteResourceFile($resource);
        }

        $resource->update($data);

        return redirect()
            ->route('feed')
            ->with('success', 'Recurso actualizado correctamente.');
    }

    public function destroy(Request $request, Resource $resource): RedirectResponse
    {
        if ($request->user()?->id !== $resource->user_id) {
            abort(403);
        }

        $this->deleteResourceFile($resource);
        $resource->delete();

        return redirect()
            ->route('feed')
            ->with('success', 'Recurso eliminado correctamente.');
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

    private function deleteResourceFile(Resource $resource): void
    {
        $fileUrl = (string) ($resource->file_url ?? '');

        if ($fileUrl === '') {
            return;
        }

        $path = parse_url($fileUrl, PHP_URL_PATH);

        if (!is_string($path) || $path === '') {
            return;
        }

        $normalizedPath = ltrim($path, '/');
        $bucket = (string) config('filesystems.disks.s3.bucket');

        if ($bucket !== '' && str_starts_with($normalizedPath, $bucket . '/')) {
            $normalizedPath = substr($normalizedPath, strlen($bucket) + 1);
        }

        try {
            Storage::disk('s3')->delete($normalizedPath);
        } catch (\Throwable) {
            // Ignore storage errors to avoid blocking delete/update flows.
        }
    }
}
