<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = \App\Models\Notification::where('user_id', auth()->id())->latest()->get();
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Redirect to the assigned link if available, otherwise just refresh
        if (isset($notification->data['link'])) {
            return redirect($notification->data['link']);
        }

        return back();
    }
}
