<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Resource;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FeedController extends Controller{
    public function index(): View{
        $feedCache = Cache::store('file');
        $currentUser = request()->user();
        $isStudent = strtoupper((string) ($currentUser?->role ?? '')) === 'STUDENT';

        $courses = $feedCache->remember('feed:v2:courses:with-subjects', now()->addMinutes(30), function () {
            return Course::with('subjects')
                ->orderBy('name')
                ->get();
        });

        $resourceQuery = Resource::query()
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
            ])
            ->with([
                'user:id,name,surname,nickname,teacher_status,is_private',
                'course:id,name',
                'subject:id,name',
            ])
            ->whereHas('user', function ($query) {
                $query->where('is_private', false);
            });

        if ($isStudent) {
            $resourceQuery->where('type', '!=', 'exam');
        }

        $resources = $resourceQuery
            ->latest()
            ->simplePaginate(10)
            ->withQueryString();

        foreach ($resources->items() as $resource) {
            if ($resource instanceof Resource) {
                $resource->display_url = $this->resolveDisplayUrl($resource);
            }
        }

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

    private function resolveDisplayUrl(Resource $resource): ?string
    {
        $fileUrl = (string) ($resource->file_url ?? '');

        if ($fileUrl === '') {
            return null;
        }

        return route('resources.preview', ['resource' => $resource->id]);
    }
}