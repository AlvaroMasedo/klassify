<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\SuggestedTeachersService;
use App\Services\FeaturedResourcesService;

class FeedController extends Controller
{
    public function index(Request $request): View
    {
        $feedCache = Cache::store('file');
        $isStudent = $this->isStudentUser();
        $activeTab = $this->resolveActiveTab($request);
        $viewerCourseId = $this->resolveViewerCourseId($request);

        $courses = $feedCache->remember('feed:v2:courses:with-subjects', now()->addMinutes(30), function () {
            return Course::with('subjects')
                ->orderBy('name')
                ->get();
        });

        $resources = $this->paginateResources(
            $this->applyFeedOrdering(
                $this->buildResourceQuery($isStudent),
                $activeTab,
                (int) $request->user()->id,
                $viewerCourseId
            )
        );

        $this->assignDisplayUrlsToResources($resources->items());

        $featuredProbe = app(FeaturedResourcesService::class)
            ->forUser($request->user(), 6);

        $featuredResources = $featuredProbe->take(5)->values();
        $featuredResourcesHasMore = $featuredProbe->count() > 5;

        $suggestedProbe = app(SuggestedTeachersService::class)
            ->forUser($request->user(), 6);

        $suggestedTeachers = $suggestedProbe->take(5)->values();
        $suggestedTeachersHasMore = $suggestedProbe->count() > 5;

        return view('feed.index', [
            'courses' => $courses,
            'resources' => $resources,
            'featuredResources' => $featuredResources,
            'featuredResourcesHasMore' => $featuredResourcesHasMore,
            'suggestedTeachers' => $suggestedTeachers,
            'suggestedTeachersHasMore' => $suggestedTeachersHasMore,
            'activeTab' => $activeTab,
        ]);
    }

    /**
     * Devuelve recursos en formato JSON para carga dinámica.
     */
    public function resources(Request $request): JsonResponse
    {
        $isStudent = $this->isStudentUser();
        $activeTab = $this->resolveActiveTab($request);
        $viewerCourseId = $this->resolveViewerCourseId($request);

        $resources = $this->paginateResources(
            $this->applyFeedOrdering(
                $this->buildResourceQuery($isStudent),
                $activeTab,
                (int) $request->user()->id,
                $viewerCourseId
            )
        );

        $this->assignDisplayUrlsToResources($resources->items());

        $html = '';

        foreach ($resources->items() as $resource) {
            $html .= view('feed.partials.resource-card', [
                'resource' => $resource,
            ])->render();
        }

        return response()->json([
            'html' => $html,
            'next_page_url' => $resources->nextPageUrl(),
            'has_more' => $resources->hasMorePages(),
        ]);
    }

    private function resolveActiveTab(Request $request): string
    {
        return $request->query('tab') === 'following' ? 'following' : 'for-you';
    }

    private function resolveViewerCourseId(Request $request): ?int
    {
        $viewer = $request->user();

        if (!$viewer) {
            return null;
        }

        if (!empty($viewer->course_id)) {
            return (int) $viewer->course_id;
        }

        $specialization = trim((string) ($viewer->specialization ?? ''));

        if ($specialization !== '') {
            $courseId = Course::query()
                ->where('name', $specialization)
                ->value('id');

            return $courseId ? (int) $courseId : null;
        }

        return null;
    }

    private function applyFeedOrdering($query, string $activeTab, int $viewerId, ?int $viewerCourseId)
    {
        if ($activeTab === 'following') {
            $followedIds = DB::table('follows')
                ->where('follower_id', $viewerId)
                ->pluck('followed_id')
                ->map(fn($id) => (int) $id)
                ->all();

            if (empty($followedIds)) {
                return $query
                    ->orderByDesc('likes_count')
                    ->latest();
            }

            $placeholders = implode(',', array_fill(0, count($followedIds), '?'));

            return $query
                ->orderByRaw(
                    "CASE WHEN resources.user_id IN ($placeholders) THEN 0 ELSE 1 END",
                    $followedIds
                )
                ->latest();
        }

        if ($viewerCourseId) {
            $query->orderByRaw(
                'CASE WHEN resources.course_id = ? THEN 0 ELSE 1 END',
                [$viewerCourseId]
            );
        }

        return $query
            ->orderByDesc('likes_count')
            ->latest();
    }

    private function paginateResources($query)
    {
        return $query
            ->simplePaginate(10)
            ->withQueryString()
            ->withPath(route('feed.resources'));
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
     */
    private function isStudentUser(): bool
    {
        $currentUser = request()->user();

        return strtoupper((string) ($currentUser?->role ?? '')) === 'STUDENT';
    }

    /**
     * Construir query base de recursos con filtros aplicables.
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
                'user:id,name,surname,nickname,role,teacher_status',
                'course:id,name',
                'subject:id,name',
            ])
            ->withCount([
                'comments',
                'favoritedBy as favorites_count',
                'likedBy as likes_count',
            ])
            ->withExists([
                'favoritedBy as is_favorited' => function ($query) {
                    $query->where('users.id', request()->user()->id);
                },
                'likedBy as is_liked' => function ($query) {
                    $query->where('users.id', request()->user()->id);
                },
            ])
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
     */
    private function assignDisplayUrlsToResources(iterable $resources): void
    {
        foreach ($resources as $resource) {
            if ($resource instanceof Resource) {
                $resource->display_url = $this->resolveDisplayUrl($resource);
            }
        }
    }

    public function moreFeaturedResources(Request $request): JsonResponse
    {
        $resources = app(FeaturedResourcesService::class)
            ->forUser($request->user(), 5, 5);

        $html = '';

        foreach ($resources as $resource) {
            $html .= view('feed.partials.featured-resource-item', [
                'resource' => $resource,
            ])->render();
        }

        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
    }

    public function moreSuggestedTeachers(Request $request): JsonResponse
    {
        $teachers = app(SuggestedTeachersService::class)
            ->forUser($request->user(), 5, 5);

        $html = '';

        foreach ($teachers as $teacher) {
            $html .= view('feed.partials.suggested-teacher-item', [
                'teacher' => $teacher,
            ])->render();
        }

        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
    }
}
