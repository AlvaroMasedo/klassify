<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Services\Resources\ResourceStorageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourcePreviewController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private ResourceStorageService $storageService) {}

    /**
     * Previsualize un recurs.
     *
     * @param Request $request
     * @param Resource $resource
     * @return Response
     */
    public function show(Request $request, Resource $resource): Response
    {
        $this->authorize('view', $resource);

        return $this->storageService->serveFileInline($resource);
    }
}
