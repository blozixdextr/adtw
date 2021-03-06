<?php

namespace App\Http\Controllers\User\Client;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Models\Mappers\LogMapper;
use App\Models\Mappers\BannerMapper;
use App\Models\Mappers\NotificationMapper;
use App\Models\User;
use App\Models\Ref;
use Redirect;
use Input;

class BannerController extends Controller
{
    public function index($userId)
    {
        $userView = User::findOrFail($userId);
        $bannerTypes = explode(',', Input::get('b', ''));
        if (count($bannerTypes) > 0) {
            $bannerTypeDefault = $bannerTypes[0];
        }

        if ($this->user->balance == 0) {
            $balanceEmpty = true;
        } else {
            $balanceEmpty = false;
        }

        return view('app.pages.user.client.banner.index', compact('userView', 'bannerTypeDefault', 'balanceEmpty'));
    }

    public function save(Request $request)
    {
        $rules = [
            'user_id' => 'required|numeric|exists:users,id',
            'banner_type' => 'required|numeric|exists:refs,id',
            'limit' => 'required|numeric|min:1|max:'.floor($this->user->availableBalance()),
            'banner' => 'required|image'
        ];
        $this->validate($request, $rules);
        $data = $request->all();
        $userId = $data['user_id'];
        $bannerType = $data['banner_type'];
        $limit = $data['limit'];

        $user = User::findOrFail($userId);
        if ($user->type != 'twitcher') {
            return Redirect::back()->withErrors(['user_id' => 'Wrong user']);
        }
        $bannerType = Ref::findOrFail($bannerType);
        if ($bannerType->type != 'banner_type') {
            return Redirect::back()->withErrors(['banner_type' => 'Wrong banner type']);
        }
        if (!BannerMapper::twitcherFree($user, $bannerType->id)) {
            return Redirect::back()->withErrors(['banner_type' => 'Twitcher already has max number of banner of this type. Please, select another banner size']);
        }
        $bannerFile = $request->file('banner');
        $realSizes = getimagesize($bannerFile);
        $requiredSizes = explode('*', $bannerType->title);
        if ($realSizes[0] != $requiredSizes[0]) {
            return Redirect::back()->withErrors(['banner' => 'Wrong width for banner. Width '.$realSizes[0].'px required but get '.$requiredSizes[0].'px']);
        }
        if ($realSizes[1] != $requiredSizes[1]) {
            return Redirect::back()->withErrors(['banner' => 'Wrong height for banner. Height '.$realSizes[1].'px required but get '.$requiredSizes[0].'px']);
        }
        $uploadDir = '/assets/app/upload/b/';
        $filePrefix = $user->id.'_'.$this->user->id.'_'.str_replace('*', '_', $bannerType->title);
        $filename = uniqid($filePrefix).'.'.$bannerFile->getClientOriginalExtension();
        $banner = $bannerFile->move(public_path($uploadDir), $filename);

        $banner = BannerMapper::addForTwitcher($user, $this->user, $bannerType, $uploadDir.$filename, $limit);
        NotificationMapper::bannerAdd($banner);
        LogMapper::log('banner_add', $banner->id);

        return Redirect::to('/user/client')->with(['success' => 'We sent your banner for twitcher\'s review']);
    }

    public function cancel($bannerId)
    {
        $banner = Banner::findOrFail($bannerId);
        if ($banner->client_id != $this->user->id) {
            return Redirect::back()->withErrors(['access' => 'You have no rights']);
        }
        $banner->status = 'finished';
        $banner->is_active = 0;
        $banner->save();
        NotificationMapper::bannerCancel($banner);

        return Redirect::back()->with(['success' => 'You canceled your order']);
    }

    public function repeat($bannerId)
    {
        $banner = Banner::findOrFail($bannerId);
        if ($banner->client_id != $this->user->id) {
            return Redirect::back()->withErrors(['access' => 'You have no rights']);
        }
        if ($banner->status != 'finished') {
            return Redirect::back()->withErrors(['access' => 'You have no rights']);
        }
        $banner->status = 'waiting';
        $banner->is_active = 0;
        $banner->save();
        NotificationMapper::bannerAdd($banner);

        return Redirect::back()->with(['success' => 'You repeated your order']);
    }
}
