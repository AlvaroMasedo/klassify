<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Resource $resource): JsonResponse
    {
        $user = $request->user();

        $exists = DB::table('favorites')
            ->where('user_id', $user->id)
            ->where('resource_id', $resource->id)
            ->exists();

        if ($exists) {
            DB::table('favorites')
                ->where('user_id', $user->id)
                ->where('resource_id', $resource->id)
                ->delete();

            $isFavorited = false;
            $message = 'Recurso eliminado de favoritos.';
        } else {
            DB::table('favorites')->insert([
                'user_id' => $user->id,
                'resource_id' => $resource->id,
                'created_at' => now(),
            ]);

            $isFavorited = true;
            $message = 'Has guardado en favoritos este recurso.';
        }

        $favoritesCount = DB::table('favorites')
            ->where('resource_id', $resource->id)
            ->count();

        return response()->json([
            'success' => true,
            'is_favorited' => $isFavorited,
            'favorites_count' => $favoritesCount,
            'message' => $message,
            'favorites_url' => route('profile.show', [
                'user' => $user->nickname,
                'tab' => 'favorites',
            ]),
        ]);
    }
}