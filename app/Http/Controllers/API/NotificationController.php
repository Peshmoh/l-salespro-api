<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // GET /api/notifications
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
                                     ->orderBy('created_at', 'desc')
                                     ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $notifications,
        ]);
    }

    // PUT /api/notifications/{id}/read
    public function markAsRead($id, Request $request)
    {
        $notification = Notification::where('user_id', $request->user()->id)
                                    ->findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }

    // PUT /api/notifications/read-all
    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }

    // DELETE /api/notifications/{id}
    public function destroy($id, Request $request)
    {
        $notification = Notification::where('user_id', $request->user()->id)
                                    ->findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted.',
        ]);
    }

    // GET /api/notifications/unread-count
    public function unreadCount(Request $request)
    {
        $count = Notification::where('user_id', $request->user()->id)
                             ->where('is_read', false)
                             ->count();

        return response()->json([
            'success' => true,
            'data'    => ['unread_count' => $count],
        ]);
    }
}
