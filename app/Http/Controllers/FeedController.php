<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Resource;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class FeedController extends Controller{
    public function index(): View{
        $courses = Course::with('subjects')
            ->orderBy('name')
            ->get();

        $resources = Resource::query()
            ->with([
                'user:id,name,surname,nickname,teacher_status',
                'course:id,name',
                'subject:id,name',
            ])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $resources->setCollection($resources->getCollection()->transform(function (Resource $resource) {
            $resource->display_url = $this->resolveDisplayUrl($resource->file_url);
            return $resource;
        }));

        $featuredResources = Resource::query()
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'type']);

        return view('feed.index', [
            'courses' => $courses,
            'resources' => $resources,
            'featuredResources' => $featuredResources,
        ]);
    }

    private function resolveDisplayUrl(?string $fileUrl): ?string
    {
        if (!$fileUrl) {
            return null;
        }

        $path = parse_url($fileUrl, PHP_URL_PATH);

        if (!is_string($path) || $path === '') {
            return $fileUrl;
        }

        $normalizedPath = ltrim($path, '/');
        $bucket = (string) config('filesystems.disks.s3.bucket');

        if ($bucket !== '' && str_starts_with($normalizedPath, $bucket . '/')) {
            $normalizedPath = substr($normalizedPath, strlen($bucket) + 1);
        }

        try {
            /** @var FilesystemAdapter $s3 */
            $s3 = Storage::disk('s3');

            return $s3->temporaryUrl($normalizedPath, now()->addMinutes(20));
        } catch (Throwable) {
            return $fileUrl;
        }
    }
}