<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    public function toggle(Request $request, Resource $resource): JsonResponse
    {
        $user = $request->user();

        $exists = DB::table('likes')
            ->where('user_id', $user->id)
            ->where('resource_id', $resource->id)
            ->exists();

        if ($exists) {
            DB::table('likes')
                ->where('user_id', $user->id)
                ->where('resource_id', $resource->id)
                ->delete();

            $isLiked = false;
        } else {
            DB::table('likes')->insertOrIgnore([
                'user_id' => $user->id,
                'resource_id' => $resource->id,
                'created_at' => now(),
            ]);

            $isLiked = true;
        }

        $likesCount = DB::table('likes')
            ->where('resource_id', $resource->id)
            ->count();

        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $likesCount,
        ]);
    }
}