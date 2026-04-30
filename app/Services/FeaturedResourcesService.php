<?php

namespace App\Services;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Support\Collection;

class FeaturedResourcesService
{
    public function forUser(User $viewer, int $limit = 5, int $offset = 0): Collection
    {
        $isStudent = strtoupper((string) ($viewer->role ?? '')) === 'STUDENT';

        $query = Resource::query()
            ->select(['id', 'user_id', 'title', 'type'])
            ->with(['user:id,is_private'])
            ->withCount([
                'comments',
                'likedBy as likes_count',
            ])
            ->withExists([
                'likedBy as is_liked' => function ($query) use ($viewer) {
                    $query->where('users.id', $viewer->id);
                },
            ])
            ->whereHas('user', function ($userQuery) {
                $userQuery->where('is_private', false);
            });

        if ($isStudent) {
            $query->where('type', '!=', 'exam');
        }

        return $query
            ->orderByDesc('likes_count')
            ->latest()
            ->skip($offset)
            ->take($limit)
            ->get();
    }
}
