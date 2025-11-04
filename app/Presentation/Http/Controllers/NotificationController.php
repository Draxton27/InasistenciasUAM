<?php

namespace App\Presentation\Http\Controllers;

use App\Presentation\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Controller: NotificationController
 * Capa: Presentation
 * Maneja las notificaciones de los usuarios
 */
class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->latest()->paginate(20);

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $user->unreadNotifications()->count(),
        ]);
    }

    public function read(Request $request, string $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }
        return back()->with('success', 'Notificación marcada como leída');
    }

    public function readAll(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Todas las notificaciones marcadas como leídas');
    }
}
