<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Resource;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function me(): RedirectResponse
    {
        $user = Auth::user();

        return redirect()->route('profile.show', [
            'user' => $user->nickname,
        ]);
    }

    public function show(Request $request, User $user): View
    {
        $user->loadMissing('institution');

        $viewer = $request->user();
        $viewerRole = strtoupper((string) ($viewer?->role ?? ''));

        $isOwner = (int) $viewer->id === (int) $user->id;
        $activeTab = (string) $request->query('tab', 'resources');

        $followersCount = DB::table('follows')
            ->where('followed_id', $user->id)
            ->count();

        $isFollowing = !$isOwner && DB::table('follows')
            ->where('follower_id', $viewer->id)
            ->where('followed_id', $user->id)
            ->exists();

        if ($activeTab === 'favorites' && !$isOwner) {
            $activeTab = 'resources';
        }
        $isAdmin = $viewerRole === 'ADMIN';

        if ($user->is_private && !$isOwner && !$isAdmin) {
            abort(403);
        }

        $selectedCourseId = (int) $request->query('course_id', 0) ?: null;
        $selectedSubjectId = (int) $request->query('subject_id', 0) ?: null;

        $allowedTypes = ['document', 'video', 'audio', 'image', 'exam', 'link'];
        $selectedTypes = array_values(array_intersect(
            (array) $request->query('types', []),
            $allowedTypes
        ));

        $isStudent = $viewerRole === 'STUDENT';

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
                'course:id,name',
                'subject:id,name',
            ])
            ->withCount([
                'comments',
                'favoritedBy as favorites_count',
            ])
            ->withExists([
                'favoritedBy as is_favorited' => function ($query) use ($viewer) {
                    $query->where('users.id', $viewer->id);
                },
            ]);

        if ($activeTab === 'favorites') {
            $resourcesQuery->whereHas('favoritedBy', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            });
        } else {
            $resourcesQuery->where('user_id', $user->id);
        }

        $resources = $resourcesQuery
            ->when($isStudent, function ($query) {
                $query->where('type', '!=', 'exam');
            })
            ->when($selectedCourseId, function ($query) use ($selectedCourseId) {
                $query->where('course_id', $selectedCourseId);
            })
            ->when($selectedSubjectId, function ($query) use ($selectedSubjectId) {
                $query->where('subject_id', $selectedSubjectId);
            })
            ->when(!empty($selectedTypes), function ($query) use ($selectedTypes) {
                $query->whereIn('type', $selectedTypes);
            })
            ->latest('updated_at')
            ->paginate(8)
            ->withQueryString();

        $courses = Course::query()
            ->with(['subjects' => function ($query) {
                $query->orderBy('name');
            }])
            ->orderBy('name')
            ->get(['id', 'name']);

        $subjects = Subject::query()
            ->when($selectedCourseId, function ($query) use ($selectedCourseId) {
                $query->where('course_id', $selectedCourseId);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'course_id']);

        $followedIds = DB::table('follows')
            ->where('follower_id', $viewer->id)
            ->pluck('followed_id')
            ->map(fn($id) => (int) $id);

        $suggestedTeachers = User::query()
            ->whereNotIn('id', array_unique([
                (int) $user->id,
                (int) $viewer->id,
            ]))
            ->where(function ($query) {
                $query
                    ->whereIn('role', ['TEACHER', 'teacher'])
                    ->orWhereIn('role', ['ADMIN', 'admin']);
            })
            ->latest()
            ->take(5)
            ->get([
                'id',
                'name',
                'surname',
                'nickname',
                'role',
                'teacher_status',
                'specialization',
            ]);

        $suggestedTeachers->each(function ($teacher) use ($followedIds) {
            $teacher->is_following = $followedIds->contains((int) $teacher->id);
        });

        return view('profile.show', [
            'profileUser' => $user,
            'resources' => $resources,
            'courses' => $courses,
            'subjects' => $subjects,
            'suggestedTeachers' => $suggestedTeachers,
            'isOwner' => $isOwner,
            'followersCount' => $followersCount,
            'isFollowing' => $isFollowing,
            'selectedCourseId' => $selectedCourseId,
            'selectedSubjectId' => $selectedSubjectId,
            'selectedTypes' => $selectedTypes,
            'activeTab' => $activeTab,
        ]);
    }
}
