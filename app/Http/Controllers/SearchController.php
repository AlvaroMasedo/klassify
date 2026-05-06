<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SearchController extends Controller
{
    public function feed(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if ($query === '') {
            return response()->json([
                'success' => true,
                'html' => '',
                'empty_query' => true,
            ]);
        }

        $viewer = $request->user();
        $isStudent = strtoupper((string) ($viewer?->role ?? '')) === 'STUDENT';

        $users = $this->searchUsers($query, $viewer->id);
        $resources = $this->searchResources($query, $viewer->id, $isStudent);

        foreach ($resources as $resource) {
            $resource->display_url = $this->resolveDisplayUrl($resource);
        }

        $html = view('feed.partials.search-results', [
            'searchQuery' => $query,
            'users' => $users,
            'resources' => $resources,
        ])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'empty_query' => false,
        ]);
    }

    private function searchUsers(string $query, int $viewerId)
    {
        $like = '%' . $query . '%';

        $usersQuery = User::query()
            ->select([
                'id',
                'name',
                'surname',
                'nickname',
                'role',
                'teacher_status',
                'specialization',
                'foto_perfil_url',
            ])
            ->where('id', '!=', $viewerId)
            ->whereRaw('UPPER(COALESCE(role, "")) = ?', ['TEACHER'])
            ->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('surname', 'like', $like)
                    ->orWhere('nickname', 'like', $like);
            })
            ->where(function ($q) {
                $q->where('is_private', false)
                    ->orWhereNull('is_private');
            })
            ->limit(5);

        $this->excludeAdmins($usersQuery);

        if (Schema::hasColumn('users', 'institution_id')) {
            $usersQuery->addSelect('institution_id')->with('institution:id,name');
        }

        if (Schema::hasColumn('users', 'course_id')) {
            $usersQuery->addSelect('course_id');
        }

        $users = $usersQuery
            ->orderBy('name')
            ->orderBy('surname')
            ->get();

        $followedIds = DB::table('follows')
            ->where('follower_id', $viewerId)
            ->pluck('followed_id')
            ->map(fn($id) => (int) $id)
            ->all();

        $users->each(function ($user) use ($followedIds) {
            $user->is_following = in_array((int) $user->id, $followedIds, true);
        });

        return $users;
    }

    private function searchResources(string $query, int $viewerId, bool $isStudent)
    {
        $like = '%' . $query . '%';

        $resourcesQuery = Resource::query()
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
                'user:id,name,surname,nickname,role,teacher_status,is_private',
                'course:id,name',
                'subject:id,name',
            ])
            ->withCount([
                'comments',
                'favoritedBy as favorites_count',
                'likedBy as likes_count',
            ])
            ->withExists([
                'favoritedBy as is_favorited' => function ($q) use ($viewerId) {
                    $q->where('users.id', $viewerId);
                },
                'likedBy as is_liked' => function ($q) use ($viewerId) {
                    $q->where('users.id', $viewerId);
                },
            ])
            ->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('course', function ($courseQuery) use ($like) {
                        $courseQuery->where('name', 'like', $like);
                    })
                    ->orWhereHas('subject', function ($subjectQuery) use ($like) {
                        $subjectQuery->where('name', 'like', $like);
                    })
                    ->orWhereHas('user', function ($userQuery) use ($like) {
                        $userQuery
                            ->where('name', 'like', $like)
                            ->orWhere('surname', 'like', $like)
                            ->orWhere('nickname', 'like', $like);
                    });
            })
            ->whereHas('user', function ($userQuery) {
                $userQuery->where(function ($q) {
                    $q->where('is_private', false)
                        ->orWhereNull('is_private');
                });

                $this->excludeAdmins($userQuery);
            });

        if ($isStudent) {
            $resourcesQuery->where('type', '!=', 'exam');
        }

        return $resourcesQuery
            ->orderByDesc('likes_count')
            ->latest()
            ->limit(10)
            ->get();
    }

    private function excludeAdmins($query): void
    {
        if (Schema::hasColumn('users', 'role')) {
            $query->whereRaw('UPPER(COALESCE(role, "")) != ?', ['ADMIN']);
        }

        if (Schema::hasColumn('users', 'admin')) {
            $query->where(function ($q) {
                $q->where('admin', '!=', 1)
                    ->orWhereNull('admin');
            });
        }

        if (Schema::hasColumn('users', 'is_admin')) {
            $query->where(function ($q) {
                $q->where('is_admin', '!=', 1)
                    ->orWhereNull('is_admin');
            });
        }
    }

    private function resolveDisplayUrl(Resource $resource): ?string
    {
        $fileUrl = (string) ($resource->file_url ?? '');

        if ($fileUrl === '') {
            return null;
        }

        return route('resources.preview', ['resource' => $resource->id]);
    }
}
