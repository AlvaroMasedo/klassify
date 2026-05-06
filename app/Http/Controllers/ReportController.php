<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Report;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function storeResource(Request $request, Resource $resource): JsonResponse
    {
        if ((int) $resource->user_id === (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes denunciar tu propio recurso.',
            ], 403);
        }

        $data = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $alreadyReported = Report::query()
            ->where('reporter_id', $request->user()->id)
            ->where('resource_id', $resource->id)
            ->whereNull('comment_id')
            ->exists();

        if ($alreadyReported) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has denunciado este recurso anteriormente.',
            ], 409);
        }

        Report::create([
            'reporter_id' => $request->user()->id,
            'resource_id' => $resource->id,
            'comment_id' => null,
            'reason' => $data['reason'],
            'status' => 'open',
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reporte completado correctamente.',
        ]);
    }

    public function storeComment(Request $request, Comment $comment): JsonResponse
    {
        if ((int) $comment->user_id === (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes denunciar tu propio comentario.',
            ], 403);
        }

        $data = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $alreadyReported = Report::query()
            ->where('reporter_id', $request->user()->id)
            ->where('comment_id', $comment->id)
            ->exists();

        if ($alreadyReported) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has denunciado este comentario anteriormente.',
            ], 409);
        }

        Report::create([
            'reporter_id' => $request->user()->id,
            'resource_id' => $comment->resource_id,
            'comment_id' => $comment->id,
            'reason' => $data['reason'],
            'status' => 'open',
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reporte completado correctamente.',
        ]);
    }
}
