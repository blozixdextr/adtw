<?php

namespace App\Http\Controllers\User\Client;

use App\Models\Mappers\NotificationMapper;
use App\Models\Notification;

class IndexController extends Controller
{
    public function index()
    {
        $notifications = NotificationMapper::fresh($this->user, 10);

        $showWelcome = $this->user->is_welcomed == 0;

        if ($showWelcome) {
            $this->user->is_welcomed = 1;
            $this->user->save();
        }

        return view('app.pages.user.client.index', compact('notifications', 'showWelcome'));
    }
}
