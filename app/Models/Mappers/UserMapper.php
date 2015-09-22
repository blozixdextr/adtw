<?php

namespace App\Models\Mappers;

use App\Models\User ;
use App\Models\UserAuthToken;
use App\Models\UserProfile;
use Request;

class UserMapper
{

    public function getNotifications() {

    }

    public static function generateAuthToken(User $user)
    {
        $email = $user->getEmailForPasswordReset();
        $token = uniqid('adtw', true);
        $authToken = UserAuthToken::create([
            'ip' => Request::ip(),
            'user_id' => $user->id,
            'token' => $token,
        ]);

        return $authToken;
    }

    public static function checkAuthToken(User $user, $token, $checkIp = false)
    {
        $authToken = $user->authToken()->orderBy('created_at', 'desc')->first();
        if ($authToken->token != $token) {
            return false;
        }
        if ($checkIp && $authToken->ip != Request::ip()) {
            throw new \Exception('IP mismatch');
        }

        return true;
    }

    public static function getByEmail($email, $type = 'client')
    {
        $user = User::where(['email' => $email])->where(['type' => $type])->first();

        return $user;
    }

    public static function findTwitchers($filters, $limit = 30)
    {
        $user = User::whereType('twitcher');
        $user->distinct();
        $user->select(['users.*']);
        $user->leftJoin('ref_user', 'users.id', '=', 'ref_user.user_id');
        $refs = [];
        if (isset($filters['name']) && $filters['name'] != '') {
            $user->where('name', 'like', '%'.$filters['name'].'%');
        }
        if (isset($filters['banner_types']) && count($filters['banner_types']) > 0) {
            $banner_types = [];
            foreach ($filters['banner_types'] as $f) {
                $t = intval($f);
                if ($t > 0) {
                    $banner_types[] = $t;
                }
            }
            $refs = array_merge($refs, $banner_types);
        }
        if (isset($filters['games']) && count($filters['games']) > 0) {
            $games = [];
            foreach ($filters['games'] as $f) {
                $t = intval($f);
                if ($t > 0) {
                    $games[] = $t;
                }
            }
            $refs = array_merge($refs, $games);
        }
        if (count($refs) > 0) {
            $user->whereIn('ref_user.ref_id', $refs);
        }
        if (isset($filters['languages']) && count($filters['languages']) > 0) {
            $languages = [];
            foreach ($filters['languages'] as $f) {
                $t = intval($f);
                if ($t > 0) {
                    $languages[] = $t;
                }
            }
            $user->whereIn('language_id', $languages);
        }
        if (isset($filters['followers']) && $filters['followers'] > 0 && $filters['followers'] != '') {
            $user->where('twitch_followers', '>=', intval($filters['followers']));
        }
        if (isset($filters['views']) && $filters['views'] > 0 && $filters['views'] != '') {
            $user->where('twitch_views', '>=', intval($filters['views']));
        }
        if (isset($filters['videos']) && $filters['videos'] > 0 && $filters['videos'] != '') {
            $user->where('twitch_videos', '>=', intval($filters['videos']));
        }
        $user->orderBy('twitch_followers', 'desc');
        $user = $user->paginate($limit);

        return $user;
    }

    public static function findTwitchers2($filters, $limit = 30)
    {
        $user = User::whereType('twitcher');
        $user->select(['users.*']);
        $user->groupBy('users.id');
        if (isset($filters['name']) && $filters['name'] != '') {
            $user->where('name', 'like', '%'.$filters['name'].'%');
        }
        if (isset($filters['banner_types']) && count($filters['banner_types']) > 0) {
            $banner_types = [];
            foreach ($filters['banner_types'] as $f) {
                $t = intval($f);
                if ($t > 0) {
                    $banner_types[] = $t;
                }
            }
            if (count($banner_types) > 0) {
                $user->join('ref_user as ref_banner', 'users.id', '=', 'ref_banner.user_id');
                $user->whereIn('ref_banner.ref_id', $banner_types);
            }

        }
        if (isset($filters['games']) && count($filters['games']) > 0) {
            $games = [];
            foreach ($filters['games'] as $f) {
                $t = intval($f);
                if ($t > 0) {
                    $games[] = $t;
                }
            }
            if (count($games) > 0) {
                $user->join('ref_user as ref_games', 'users.id', '=', 'ref_games.user_id');
                $user->whereIn('ref_games.ref_id', $games);
            }
        }
        if (isset($filters['languages']) && count($filters['languages']) > 0) {
            $languages = [];
            foreach ($filters['languages'] as $f) {
                $t = intval($f);
                if ($t > 0) {
                    $languages[] = $t;
                }
            }
            $user->whereIn('language_id', $languages);
        }
        if (isset($filters['followers']) && $filters['followers'] > 0 && $filters['followers'] != '') {
            $user->where('twitch_followers', '>=', intval($filters['followers']));
        }
        if (isset($filters['views']) && $filters['views'] > 0 && $filters['views'] != '') {
            $user->where('twitch_views', '>=', intval($filters['views']));
        }
        if (isset($filters['videos']) && $filters['videos'] > 0 && $filters['videos'] != '') {
            $user->where('twitch_videos', '>=', intval($filters['videos']));
        }
        $user->orderBy('twitch_followers', 'desc');
        $user = $user->paginate($limit);

        return $user;
    }

    public static function admin($email, $password)
    {
        $user = User::where(['email' => $email])->where(['role' => 'admin'])->first();

        return $user;
    }


}