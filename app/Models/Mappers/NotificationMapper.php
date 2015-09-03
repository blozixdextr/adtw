<?php

namespace App\Models\Mappers;

use App\Models\User;
use App\Models\Notification;
use App\Models\UserProfile;
use App\Models\Mappers\LogMapper;
use Request;
use Auth;

class NotificationMapper
{
    public static function notify(User $user, $title, $type = 'default', $subtitle = '') {
        $l = Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'subtitle' => $subtitle,
            'type' => $type
        ]);

        return $l;
    }

    public static function reviewed(Notification $notification) {
        $notification->seen_at = \Carbon\Carbon::now();
        $notification->save();
    }

    public static function type(User $user, $type, $onlyFresh = true, $limit = 50) {
        if (is_string($type)) {
            $type = [$type];
        }
        $notifications = Notification::where('user_id', $user->id);
        $notifications->whereIn('type', $type);
        if ($onlyFresh) {
            $notifications->where('seen_at', null);
        }
        $notifications = $notifications->paginate($limit);

        return $notifications;
    }

    public static function fresh(User $user, $limit = 50) {

        $notifications = Notification::where('user_id', $user->id);
        $notifications->where('seen_at', null);
        $notifications = $notifications->paginate($limit);

        return $notifications;
    }


}