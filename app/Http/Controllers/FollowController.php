<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    public function toggle(Request $request, User $user): JsonResponse
    {
        $viewer = $request->user();

        if ((int) $viewer->id === (int) $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes seguirte a ti mismo.',
            ], 422);
        }

        $exists = DB::table('follows')
            ->where('follower_id', $viewer->id)
            ->where('followed_id', $user->id)
            ->exists();

        if (!$exists && (bool) $user->is_private) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes seguir un perfil privado.',
            ], 403);
        }
        
        if ($exists) {
            DB::table('follows')
                ->where('follower_id', $viewer->id)
                ->where('followed_id', $user->id)
                ->delete();

            $isFollowing = false;
            $message = 'Has dejado de seguir a este usuario.';
        } else {
            DB::table('follows')->insertOrIgnore([
                'follower_id' => $viewer->id,
                'followed_id' => $user->id,
                'created_at' => now(),
            ]);

            $isFollowing = true;
            $message = 'Ahora sigues a este usuario.';
        }

        $followersCount = DB::table('follows')
            ->where('followed_id', $user->id)
            ->count();

        return response()->json([
            'success' => true,
            'is_following' => $isFollowing,
            'followers_count' => $followersCount,
            'message' => $message,
        ]);
    }
}
