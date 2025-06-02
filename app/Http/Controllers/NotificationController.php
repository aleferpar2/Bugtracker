<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->paginate(10);

        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->update(['read' => true]);

        return back()->with('success', 'Notificación marcada como leída.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        auth()->user()->notifications()
            ->where('read', false)
            ->update(['read' => true]);

        return back()->with('success', 'Todas las notificaciones han sido marcadas como leídas.');
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);

        $notification->delete();

        return back()->with('success', 'Notificación eliminada.');
    }

    /**
     * Delete all notifications.
     */
    public function destroyAll()
    {
        auth()->user()->notifications()->delete();

        return back()->with('success', 'Todas las notificaciones han sido eliminadas.');
    }

    /**
     * Get unread notifications count.
     */
    public function unreadCount()
    {
        $count = auth()->user()->notifications()
            ->where('read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get latest notifications for the navbar.
     */
    public function latest()
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->take(5)
            ->get();

        return response()->json($notifications);
    }
} 