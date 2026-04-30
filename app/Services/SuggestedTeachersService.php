<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SuggestedTeachersService
{
    public function forUser(User $viewer, int $limit = 5): Collection
    {
        $viewerInstitutionId = (int) ($viewer->institution_id ?? 0) ?: null;

        $usersHasCourseId = Schema::hasColumn('users', 'course_id');

        $viewerCourseId = $usersHasCourseId
            ? ((int) ($viewer->getAttribute('course_id') ?? 0) ?: null)
            : null;

        $viewerSpecialization = strtolower(trim((string) ($viewer->specialization ?? '')));

        $followedIds = DB::table('follows')
            ->where('follower_id', $viewer->id)
            ->pluck('followed_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $columns = [
            'id',
            'name',
            'surname',
            'nickname',
            'role',
            'teacher_status',
            'specialization',
            'institution_id',
        ];

        if ($usersHasCourseId) {
            $columns[] = 'course_id';
        }

        $query = User::query()
            ->where('id', '!=', $viewer->id)
            ->where(function ($query) {
                $query
                    ->whereIn('role', ['TEACHER', 'teacher'])
                    ->orWhereIn('role', ['ADMIN', 'admin']);
            })
            ->where(function ($query) {
                $query
                    ->where('is_private', false)
                    ->orWhereNull('is_private');
            })
            ->with('institution:id,name');

        if ($viewerInstitutionId) {
            $query->orderByRaw(
                'CASE WHEN institution_id = ? THEN 0 ELSE 1 END',
                [$viewerInstitutionId]
            );
        }

        if ($usersHasCourseId && $viewerCourseId) {
            $query->orderByRaw(
                'CASE WHEN course_id = ? THEN 0 ELSE 1 END',
                [$viewerCourseId]
            );
        } elseif ($viewerSpecialization !== '') {
            $query->orderByRaw(
                'CASE WHEN LOWER(specialization) = ? THEN 0 ELSE 1 END',
                [$viewerSpecialization]
            );
        }

        $teachers = $query
            ->latest()
            ->take($limit)
            ->get($columns);

        $teachers->each(function ($teacher) use ($followedIds) {
            $teacher->is_following = in_array((int) $teacher->id, $followedIds, true);
        });

        return $teachers;
    }
}