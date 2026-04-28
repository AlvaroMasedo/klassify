<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Almacena un nuevo comentario en un recurso.
     *
     * @param Request $request
     * @param Resource $resource
     * @return JsonResponse
     */
    public function store(Request $request, Resource $resource): JsonResponse
    {
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
}
