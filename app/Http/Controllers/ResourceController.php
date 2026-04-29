<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Models\Resource;
use App\Models\Course;
use App\Services\Resources\ResourceStorageService;
use App\Services\Resources\ResourceTypeResolver;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ResourceController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private ResourceStorageService $storageService,
        private ResourceTypeResolver $typeResolver
    ) {}

    public function entry(Request $request): RedirectResponse
    {
        $this->authorize('create', Resource::class);

        $user = $request->user();
        $role = strtoupper((string) ($user?->role ?? ''));

        if ($role === 'ADMIN') {
            return redirect()->route('admin.resources.create');
        }

        return redirect()->route('teacher.resources.create');
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Resource::class);

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
        $this->authorize('update', $resource);

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
        $this->authorize('create', Resource::class);

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

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $resourceType = $request->boolean('is_exam') ? 'exam' : $this->typeResolver->resolve($extension);

        if (!$resourceType) {
            throw ValidationException::withMessages([
                'resource_file' => 'El tipo de archivo no está permitido.',
            ]);
        }

        try {
            $fileData = $this->storageService->uploadFile($file, $userId);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo subir el archivo. Inténtalo de nuevo.');
        }

        Resource::create([
            'user_id' => $userId,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $resourceType,
            'course_id' => $validated['course_id'] ?? null,
            'subject_id' => $validated['subject_id'] ?? null,
            'file_url' => $fileData['url'],
            'file_name' => $fileData['name'],
            'file_size' => $fileData['size'],
            'mime_type' => $fileData['mime'],
        ]);

        return redirect()
            ->route('feed')
            ->with('success', 'Recurso subido correctamente.');
    }

    public function update(UpdateResourceRequest $request, Resource $resource): RedirectResponse
    {
        $this->authorize('update', $resource);

        $validated = $request->validated();
        $file = $request->file('resource_file');
        $currentExtension = strtolower(pathinfo((string) ($resource->file_name ?? ''), PATHINFO_EXTENSION));
        $nextExtension = $file ? strtolower((string) $file->getClientOriginalExtension()) : $currentExtension;
        $resourceType = $request->boolean('is_exam') ? 'exam' : $this->typeResolver->resolve($nextExtension);

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
            try {
                $fileData = $this->storageService->replaceFile($file, $resource);
                $data['file_url'] = $fileData['url'];
                $data['file_name'] = $fileData['name'];
                $data['file_size'] = $fileData['size'];
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'No se pudo subir el archivo. Inténtalo de nuevo.');
            }
        }

        $resource->update($data);

        return redirect()
            ->route('feed')
            ->with('success', 'Recurso actualizado correctamente.');
    }

    public function destroy(Request $request, Resource $resource): RedirectResponse
    {
        $this->authorize('delete', $resource);

        $this->storageService->deleteFile($resource);
        $resource->delete();

        return redirect()
            ->route('feed')
            ->with('success', 'Recurso eliminado correctamente.');
    }

    public function show(Resource $resource): View
    {
        // Validar permisos usando la policy
        $this->authorize('view', $resource);

        // Cargar relaciones del recurso
        $resource->load(['user', 'course', 'subject'])->loadCount('comments');
        
        // Asignar display_url al recurso
        $fileUrl = (string) ($resource->file_url ?? '');
        $resource->display_url = $fileUrl !== '' ? route('resources.preview', ['resource' => $resource->id]) : null;

        // Cargar comentarios con usuario, ordenados por fecha descendente
        $comments = $resource->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Cargar recursos destacados (mismo que en FeedController)
        $currentUser = request()->user();
        $isStudent = strtoupper((string) ($currentUser?->role ?? '')) === 'STUDENT';
        
        $featuredResources = Resource::query()
            ->latest()
            ->select(['id', 'user_id', 'title', 'type'])
            ->with(['user:id,is_private'])
            ->whereHas('user', function ($userQuery) {
                $userQuery->where('is_private', false);
            });

        if ($isStudent) {
            $featuredResources->where('type', '!=', 'exam');
        }

        $featuredResources = $featuredResources->take(5)->get();

        return view('feed.show-resource', [
            'resource' => $resource,
            'comments' => $comments,
            'commentsCount' => $comments->count(),
            'featuredResources' => $featuredResources,
        ]);
    }

    private function resolveScope(Request $request): string
    {
        return $request->routeIs('admin.*') ? 'admin' : 'teacher';
    }
}
