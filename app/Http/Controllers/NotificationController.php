<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $userId = (int) $request->user()->id;

        $notifications = UserNotification::query()
            ->with([
                'actor:id,name,surname,nickname,foto_perfil_url',
                'resource:id,title',
            ])
            ->where('recipient_id', $userId)
            ->latest('created_at')
            ->paginate(20);

        UserNotification::query()
            ->where('recipient_id', $userId)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }
}