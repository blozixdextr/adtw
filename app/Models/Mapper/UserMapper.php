<?php

namespace App\Models\Mappers;

use App\Models\User ;
use App\Models\UserProfile ;
use \Laravel\Socialite\Contracts\User as SocialiteUser;

class UserMapper
{


    public function getNotifications() {

    }

    public static function getAvatarDir() {
        return public_path(User::avatarDir);
    }

    public static function generateAvatarPath(User $user, $format = 'jpg') {
        return self::getAvatarDir().'/'.$user->id.'.'.$format;
    }

    public static function getAvatarSrc(UserProfile $profile) {
        if ($profile->avatar) {
            $avatar = $profile->avatar;
        } else {
            $avatar = 'user.jpg';
        }

        return '/'.User::avatarDir.'/'.$avatar;
    }

    public static function confirmFacebook(User $localUser, SocialiteUser $facebookUser) {
        $nickname = $facebookUser->getNickname();
        if ($nickname == '') {
            $nickname = $facebookUser->getId();
        }
        $profile = $localUser->profile;
        $profile->facebook = 'http://facebook.com/'.$nickname;
        $profile->confirmed_facebook = true;
        $profile->save();

        return $profile;
    }
}