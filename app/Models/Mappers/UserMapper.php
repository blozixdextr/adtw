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

    public static function getByEmail($email)
    {
        $user = User::where(['email' => $email])->first();

        return $user;
    }


}