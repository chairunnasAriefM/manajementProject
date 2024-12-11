<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Pagination


        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead()
    {
        Notification::where('user_id', auth()->id())->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
