<?php

namespace App\Models\Mappers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Log;
use Request;
use Auth;

class LogMapper
{
    public static function log($type, $value, $additional = null, $vars = null) {
        $ip = Request::ip();
        $user = Auth::user();
        if ($user) {
            $user_id = $user->id;
        } else {
            $user_id = 0;
        }
        $l = Log::create([
            'user_id' => $user_id,
            'ip' => $ip,
            'type' => $type,
            'key_value' => $value,
            'additional_value' => $additional,
            'var' => $vars
        ]);

        return $l;
    }

    public static function reviewed(Log $log) {
        $log->review_date = \Carbon\Carbon::now();
        $log->save();
    }

    public static function type($type, $onlyFresh = true, $limit = 50) {
        if (is_string($type)) {
            $type = [$type];
        }
        $logs = Log::whereIn('type', $type);
        if ($onlyFresh) {
            $logs->where('review_date', null);
        }
        $logs = $logs->paginate($limit);

        return $logs;
    }


}