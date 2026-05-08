<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Course;
use App\Models\Resource;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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
        $isAdmin = $viewerRole === 'ADMIN';
        $activeTab = (string) $request->query('tab', 'resources');

        if ($activeTab === 'favorites' && !$isOwner) {
            $activeTab = 'resources';
        }

        $isPrivateBlocked = (bool) $user->is_private && !$isOwner;
        $showSocialInfo = true;
        $canShowFollowButton = !$isOwner && !(bool) $user->is_private;

        $followersCount = $showSocialInfo
            ? DB::table('follows')->where('followed_id', $user->id)->count()
            : 0;

        $isFollowing = $canShowFollowButton && DB::table('follows')
            ->where('follower_id', $viewer->id)
            ->where('followed_id', $user->id)
            ->exists();

        $selectedCourseId = (int) $request->query('course_id', 0) ?: null;
        $selectedSubjectId = (int) $request->query('subject_id', 0) ?: null;
        $selectedTypes = $this->parseSelectedTypes($request);

        $courses = $this->getCoursesWithSubjects();

        $subjects = $this->getSubjects($selectedCourseId);

        $resources = $isPrivateBlocked
            ? new LengthAwarePaginator(
                [],
                0,
                8,
                null,
                ['path' => route('profile.show', ['user' => $user->nickname])]
            )
            : $this->getProfileResources(
                request: $request,
                profileUser: $user,
                activeTab: $activeTab,
                selectedCourseId: $selectedCourseId,
                selectedSubjectId: $selectedSubjectId,
                selectedTypes: $selectedTypes
            );

        $suggestedTeachers = $this->getSuggestedTeachers($user, $viewer);

        return view('profile.show', [
            'profileUser' => $user,
            'resources' => $resources,
            'courses' => $courses,
            'subjects' => $subjects,
            'suggestedTeachers' => $suggestedTeachers,
            'isOwner' => $isOwner,
            'isAdmin' => $isAdmin,
            'followersCount' => $followersCount,
            'isFollowing' => $isFollowing,
            'showSocialInfo' => $showSocialInfo,
            'canShowFollowButton' => $canShowFollowButton,
            'selectedCourseId' => $selectedCourseId,
            'selectedSubjectId' => $selectedSubjectId,
            'selectedTypes' => $selectedTypes,
            'activeTab' => $activeTab,
            'isPrivateBlocked' => $isPrivateBlocked,
        ]);
    }

    public function resources(Request $request, User $user): JsonResponse
    {
        $user->loadMissing('institution');

        $viewer = $request->user();
        $viewerRole = strtoupper((string) ($viewer?->role ?? ''));

        $isOwner = (int) $viewer->id === (int) $user->id;
        $isAdmin = $viewerRole === 'ADMIN';
        $activeTab = (string) $request->query('tab', 'resources');

        if ($activeTab === 'favorites' && !$isOwner) {
            $activeTab = 'resources';
        }

        if ((bool) $user->is_private && !$isOwner) {
            return response()->json([
                'success' => true,
                'html' => view('profile.partials.private-resources')->render(),
                'pagination_html' => '',
            ]);
        }

        $selectedCourseId = (int) $request->query('course_id', 0) ?: null;
        $selectedSubjectId = (int) $request->query('subject_id', 0) ?: null;
        $selectedTypes = $this->parseSelectedTypes($request);

        $resources = $this->getProfileResources(
            request: $request,
            profileUser: $user,
            activeTab: $activeTab,
            selectedCourseId: $selectedCourseId,
            selectedSubjectId: $selectedSubjectId,
            selectedTypes: $selectedTypes
        );

        $html = '';

        foreach ($resources->items() as $resource) {
            $html .= view('profile.partials.resource-card', [
                'resource' => $resource,
            ])->render();
        }

        if ($html === '') {
            $html = view('profile.partials.resources-empty', [
                'activeTab' => $activeTab,
            ])->render();
        }

        return response()->json([
            'success' => true,
            'html' => $html,
            'pagination_html' => $resources->hasPages()
                ? view('profile.partials.pagination', ['resources' => $resources])->render()
                : '',
        ]);
    }

    public function edit(Request $request, User $user): View
    {
        abort_unless((int) $request->user()->id === (int) $user->id, 403);

        $courses = Course::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('profile.edit', [
            'profileUser' => $user,
            'courses' => $courses,
        ]);
    }

    public function update(UpdateProfileRequest $request, User $user): RedirectResponse
    {
        abort_unless((int) $request->user()->id === (int) $user->id, 403);

        $validated = $request->validated();

        $data = [
            'nickname' => $validated['nickname'],
            'specialization' => $validated['specialization'] ?? null,
            'is_private' => $request->boolean('is_private'),
            'description' => trim((string) ($validated['description'] ?? '')) !== ''
                ? trim((string) $validated['description'])
                : null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('foto_perfil')) {
            try {
                $data['foto_perfil_url'] = $this->uploadProfilePhoto(
                    $request->file('foto_perfil'),
                    $user
                );

                $this->deleteOldProfilePhoto($user->foto_perfil_url);
            } catch (\Throwable $e) {
                Log::error('Error al subir foto de perfil', [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'user_id' => $user->id,
                ]);
                throw ValidationException::withMessages([
                    'foto_perfil' => 'No se pudo subir la foto de perfil. Inténtalo de nuevo.',
                ]);
            }
        }

        $user->update($data);

        return redirect()
            ->route('profile.show', ['user' => $user->fresh()->nickname])
            ->with('success', 'Perfil actualizado correctamente.');
    }

    private function getProfileResources(
        Request $request,
        User $profileUser,
        string $activeTab,
        ?int $selectedCourseId,
        ?int $selectedSubjectId,
        array $selectedTypes
    ) {
        $viewer = $request->user();
        $viewerRole = strtoupper((string) ($viewer?->role ?? ''));
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
                'user:id,name,surname,nickname,role,teacher_status,foto_perfil_url',
                'course:id,name',
                'subject:id,name',
            ])
            ->withCount([
                'comments',
                'favoritedBy as favorites_count',
                'likedBy as likes_count',
            ])
            ->withExists([
                'favoritedBy as is_favorited' => function ($query) use ($viewer) {
                    $query->where('users.id', $viewer->id);
                },
                'likedBy as is_liked' => function ($query) use ($viewer) {
                    $query->where('users.id', $viewer->id);
                },
            ]);

        if ($activeTab === 'favorites') {
            $resourcesQuery->whereHas('favoritedBy', function ($query) use ($profileUser) {
                $query->where('users.id', $profileUser->id);
            });
        } else {
            $resourcesQuery->where('user_id', $profileUser->id);
        }

        return $resourcesQuery
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
            ->withQueryString()
            ->withPath(route('profile.resources', ['user' => $profileUser->nickname]));
    }

    private function getCoursesWithSubjects()
    {
        return Course::query()
            ->with(['subjects' => function ($query) {
                $query->orderBy('name');
            }])
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function getSubjects(?int $selectedCourseId)
    {
        return Subject::query()
            ->when($selectedCourseId, function ($query) use ($selectedCourseId) {
                $query->where('course_id', $selectedCourseId);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'course_id']);
    }

    private function parseSelectedTypes(Request $request): array
    {
        $allowedTypes = ['document', 'video', 'audio', 'image', 'exam', 'link'];
        $selectedTypes = $request->query('types', []);

        if (is_string($selectedTypes)) {
            $selectedTypes = explode(',', $selectedTypes);
        }

        return array_values(array_intersect((array) $selectedTypes, $allowedTypes));
    }

    private function getSuggestedTeachers(User $profileUser, User $viewer)
    {
        $followedIds = DB::table('follows')
            ->where('follower_id', $viewer->id)
            ->pluck('followed_id')
            ->map(fn($id) => (int) $id);

        $suggestedTeachers = User::query()
            ->whereNotIn('id', array_unique([
                (int) $profileUser->id,
                (int) $viewer->id,
            ]))

            ->whereRaw('UPPER(COALESCE(role, "")) = ?', ['TEACHER'])
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
                'foto_perfil_url',
            ]);

        $suggestedTeachers->each(function ($teacher) use ($followedIds) {
            $teacher->is_following = $followedIds->contains((int) $teacher->id);
        });

        return $suggestedTeachers;
    }

    private function uploadProfilePhoto($file, User $user): string
    {
        $extension = strtolower((string) $file->getClientOriginalExtension());
        $fileName = Str::uuid() . '.' . $extension;
        $folder = 'profiles/' . $user->id;

        try {
            $path = Storage::disk('s3')->putFileAs(
                $folder,
                $file,
                $fileName,
                [
                    'ContentType' => $file->getMimeType(),
                ]
            );

            if ($path === false) {
                Log::warning('S3 putFileAs retornó false', [
                    'folder' => $folder,
                    'fileName' => $fileName,
                    'mimeType' => $file->getMimeType(),
                    'user_id' => $user->id,
                ]);
                throw new \RuntimeException('S3 no pudo procesar la carga (putFileAs retornó false)');
            }

            /** @var FilesystemAdapter $s3 */
            $s3 = Storage::disk('s3');

            return $s3->url($path);
        } catch (\Exception $e) {
            Log::error('Excepción al subir a S3', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'folder' => $folder,
                'fileName' => $fileName,
                'user_id' => $user->id,
            ]);
            throw $e;
        }
    }

    private function deleteOldProfilePhoto(?string $url): void
    {
        if (!$url) {
            return;
        }

        try {
            // Extraer la ruta relativa de la URL pública
            // URL típica: https://klassify-files-alvaro.s3.eu-south-2.amazonaws.com/profiles/13/uuid.jpg
            $parsed = parse_url($url, PHP_URL_PATH);

            if (is_string($parsed) && $parsed !== '') {
                $path = ltrim($parsed, '/');
                // Si la ruta incluye el bucket (algunas URLs), eliminar prefijo
                $bucket = (string) config('filesystems.disks.s3.bucket');
                if ($bucket !== '' && str_starts_with($path, $bucket . '/')) {
                    $path = substr($path, strlen($bucket) + 1);
                }

                Storage::disk('s3')->delete($path);
            }
        } catch (\Throwable) {
            // No bloqueamos el guardado del perfil si falla borrar la imagen antigua.
        }
    }
}
