<?php

namespace App\Http\Controllers\User\Twitcher;

use App\Models\Mappers\BannerMapper;
use Auth;
use App\Models\Mappers\LogMapper;
use App\Apis\Twitch;
use App\Models\Mappers\NotificationMapper;
use App\Models\Notification;
use Session;

class IndexController extends Controller
{
    public function index() {
        $this->updateStatistics();
        $banners = [];
        $bannerTypes = $this->user->bannerTypes;
        $notifications = NotificationMapper::fresh($this->user, 10);

        $waitingBanners = BannerMapper::waitingTwitcher($this->user);

        foreach ($bannerTypes as $bt) {
            $banners[$bt->id] = BannerMapper::activeTwitcher($this->user, $bt->id);
        }

        $activeBanners = BannerMapper::activeTwitcher($this->user);

        $showWelcome = $this->user->is_welcomed == 0;

        if ($showWelcome) {
            $this->user->is_welcomed = 1;
            $this->user->save();
        }

        return view('app.pages.user.twitcher.index', compact('banners', 'bannerTypes', 'notifications', 'waitingBanners', 'activeBanners', 'showWelcome'));
    }

    public function updateStatistics() {
        if (Session::get('fake-auth', 0) == 1) {
            return false;
        }
        $user = $this->user;
        $now = \Carbon\Carbon::now();
        if ($user->twitch_updated == null || true /*$now->diffInDays($user->twitch_updated, false) < 0*/) {
            $twitch = app('twitch');
            $channel = $twitch->getChannel();
            if ($channel && isset($channel->views)) {
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
                return true;
            }
        }

        return false;
    }
}
