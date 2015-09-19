<?php

namespace App\Http\Controllers\User\Client;

use App\Models\Mappers\NotificationMapper;
use App\Models\Notification;

class IndexController extends Controller
{
    public function index()
    {
        $notifications = NotificationMapper::fresh($this->user);

        return view('app.pages.user.client.index', compact('notifications'));
    }
}
