<?php

namespace App\Http\Controllers\User\Client;

use App\Models\Mappers\NotificationMapper;
use App\Models\Notification;
use Redirect;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = NotificationMapper::user($this->user);

        return view('app.pages.user.client.notification.index', compact('notifications'));
    }

    public function reviewed($notificationId, Request $request)
    {
        $notification = Notification::findOrFail($notificationId);
        if ($notification->user_id != $this->user->id) {
            if ($request->ajax()) {
                return ['result' => false];
            } else {
                return Redirect::back()->withErrors(['access' => 'You have no right for this']);
            }
        }

        $result = NotificationMapper::reviewed($notification);
        if ($request->ajax()) {
            return ['result' => $result];
        } else {
            if ($result) {
                return Redirect::back()->with(['success' => 'You have no right for this']);
            } else {
                return Redirect::back()->withErrors(['general' => 'Something was wrong']);
            }
        }
    }

}