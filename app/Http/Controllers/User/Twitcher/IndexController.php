<?php

namespace App\Http\Controllers\User\Twitcher;

use Auth;
use App\Models\Mappers\LogMapper;
use App\Apis\Twitch;

class IndexController extends Controller
{
    public function index() {
        $this->updateStatistics();
        $user = $this->user;
        $profile = $user->profile;

        return view('app.user.twitcher.index', compact('user', 'profile'));
    }

    public function updateStatistics() {
        $user = $this->user;
        $now = \Carbon\Carbon::now();
        if ($user->twitch_updated == null || $now->diffInDays($user->twitch_updated, false) < 0) {
            $twitch = app('twitch');
            $channel = $twitch->getChannel();
            if ($channel) {
                $views = $channel->views;
                $followers = $channel->followers;
                $videos =  $twitch->getVideos($channel->name, 2);
                $videos = $videos->_total;
                $user->twitch_views = $views;
                $user->twitch_followers = $followers;
                $user->twitch_videos = $videos;
                $user->twitch_channel = $channel;
                $user->twitch_updated = $now;
                $user->save();
            }
        }
    }
}
