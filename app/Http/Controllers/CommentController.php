<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserNotification;

class CommentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Almacena un nuevo comentario en un recurso.
     *
     * @param Request $request
     * @param Resource $resource
     * @return JsonResponse
     */
    public function store(Request $request, Resource $resource): JsonResponse
    {
        // Validar permisos usando la policy
        $this->authorize('view', $resource);

        // Validar el comentario
        $validated = $request->validate([
            'comment' => 'required|string|max:750',
        ], [
            'comment.required' => 'El comentario no puede estar vacío.',
            'comment.string' => 'El comentario debe ser texto.',
            'comment.max' => 'El comentario no puede exceder 750 caracteres.',
        ]);

        // Crear el comentario
        $comment = Comment::create([
            'resource_id' => $resource->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        // Crear notificación para el propietario del recurso, si el comentario no es del mismo usuario
        if ((int) $resource->user_id !== (int) $request->user()->id) {
            UserNotification::create([
                'recipient_id' => $resource->user_id,
                'actor_id' => $request->user()->id,
                'resource_id' => $resource->id,
                'comment_id' => $comment->id,
                'type' => 'comment',
                'created_at' => now(),
            ]);
        }

        // Cargar la relación user del comentario
        $comment->load('user');

        // Renderizar el HTML del nuevo comentario
        $html = view('feed.partials.comment', ['comment' => $comment])->render();

        // Obtener el contador actualizado de comentarios
        $commentsCount = $resource->comments()->count();

        return response()->json([
            'success' => true,
            'html' => $html,
            'comments_count' => $commentsCount,
            'message' => 'Comentario publicado correctamente.',
        ], 201);
    }
    public function destroy(Request $request, Resource $resource, Comment $comment): JsonResponse
    {
        if ((int) $comment->resource_id !== (int) $resource->id) {
            return response()->json([
                'success' => false,
                'message' => 'Comentario no encontrado.',
            ], 404);
        }

        $user = $request->user();
        $isAdmin = strtoupper((string) ($user?->role ?? '')) === 'ADMIN';
        $isOwner = (int) $comment->user_id === (int) $user?->id;

        if (!$isAdmin && !$isOwner) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar este comentario.',
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'comments_count' => $resource->comments()->count(),
            'message' => 'Comentario eliminado correctamente.',
        ]);
    }
}
