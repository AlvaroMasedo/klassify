<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Services\Resources\ResourceStorageService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourcePreviewController extends Controller
{
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
        // Autorització implícita: el recurs és públic per a usuaris autenticats
        return $this->storageService->serveFileInline($resource);
    }
}
