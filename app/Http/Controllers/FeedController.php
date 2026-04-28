<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FeedController extends Controller
{
    public function index(): View
    {
        $feedCache = Cache::store('file');
        $isStudent = $this->isStudentUser();

        $courses = $feedCache->remember('feed:v2:courses:with-subjects', now()->addMinutes(30), function () {
            return Course::with('subjects')
                ->orderBy('name')
                ->get();
        });

        $resources = $this->buildResourceQuery($isStudent)
            ->latest()
            ->simplePaginate(10)
            ->withQueryString();

        $this->assignDisplayUrlsToResources($resources->items());

        $featuredResources = $feedCache->remember('feed:v2:featured-resources:' . ($isStudent ? 'student' : 'staff'), now()->addMinutes(10), function () use ($isStudent) {
            $query = Resource::query()
                ->latest()
                ->select(['id', 'user_id', 'title', 'type'])
                ->with(['user:id,is_private']);

            $query->whereHas('user', function ($userQuery) {
                $userQuery->where('is_private', false);
            });

            if ($isStudent) {
                $query->where('type', '!=', 'exam');
            }

            return $query->take(5)->get();
        });

        return view('feed.index', [
            'courses' => $courses,
            'resources' => $resources,
            'featuredResources' => $featuredResources,
        ]);
    }

    /**
     * Devuelve recursos en formato JSON para carga dinámica
     */
    public function resources(): JsonResponse
    {
        $isStudent = $this->isStudentUser();

        $resources = $this->buildResourceQuery($isStudent)
            ->latest()
            ->simplePaginate(10)
            ->withQueryString();

        $this->assignDisplayUrlsToResources($resources->items());

        // Renderizar las tarjetas de recursos
        $html = '';
        foreach ($resources->items() as $resource) {
            $html .= view('feed.partials.resource-card', ['resource' => $resource])->render();
        }

        return response()->json([
            'html' => $html,
            'next_page_url' => $resources->nextPageUrl(),
            'has_more' => $resources->hasMorePages(),
        ]);
    }

    private function resolveDisplayUrl(Resource $resource): ?string
    {
        $fileUrl = (string) ($resource->file_url ?? '');

        if ($fileUrl === '') {
            return null;
        }

        return route('resources.preview', ['resource' => $resource->id]);
    }

    /**
     * Determinar si el usuario autenticado es estudiante.
     *
     * @return bool
     */
    private function isStudentUser(): bool
    {
        $currentUser = request()->user();
        return strtoupper((string) ($currentUser?->role ?? '')) === 'STUDENT';
    }

    /**
     * Construir query base de recursos con filtros aplicables.
     * Excluye usuarios privados y, para estudiantes, excluye exámenes.
     * Incluye relaciones necesarias para evitar N+1.
     *
     * @param bool $isStudent
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildResourceQuery(bool $isStudent)
    {
        $query = Resource::query()
            ->select([
                'id',
                'user_id',
                'course_id',
                'subject_id',
                'title',
                'description',
                'type',
                'file_url',
                'file_name',
                'mime_type',
                'created_at',
                'updated_at',
            ])
            ->with([
                'user:id,name,surname,nickname,teacher_status,is_private',
                'course:id,name',
                'subject:id,name',
            ])
            ->withCount('comments')
            ->whereHas('user', function ($q) {
                $q->where('is_private', false);
            });

        if ($isStudent) {
            $query->where('type', '!=', 'exam');
        }

        return $query;
    }

    /**
     * Asignar URLs de visualización a una colección de recursos.
     * Evita duplicación de lógica entre index() y resources().
     *
     * @param iterable $resources
     * @return void
     */
    private function assignDisplayUrlsToResources(iterable $resources): void
    {
        foreach ($resources as $resource) {
            if ($resource instanceof Resource) {
                $resource->display_url = $this->resolveDisplayUrl($resource);
            }
        }
    }
}