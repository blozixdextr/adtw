<?php

namespace App\Http\Controllers\User\Client;

use Illuminate\Http\Request;
use App\Models\Mappers\LogMapper;
use App\Models\Mappers\BannerMapper;
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

        return view('app.pages.user.client.banner.index', compact('userView', 'bannerTypeDefault'));
    }

    public function save(Request $request)
    {
        $rules = [
            'user_id' => 'required|numeric|exists:users,id',
            'banner_type' => 'required|numeric|exists:refs,id',
            'limit' => 'required|numeric|min:1:max:'.floor($this->user->availableBalance()),
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
        if ($realSizes['width'] != $requiredSizes[0]) {
            return Redirect::back()->withErrors(['banner' => 'Wrong width for banner. Width '.$realSizes['width'].'px required but get '.$requiredSizes[0].'px']);
        }
        if ($realSizes['height'] != $requiredSizes[1]) {
            return Redirect::back()->withErrors(['banner' => 'Wrong height for banner. Height '.$realSizes['width'].'px required but get '.$requiredSizes[0].'px']);
        }
        $filePrefix = $user->id.'_'.$this->user->id.'_'.str_replace('*', '_', $bannerType->title);
        $bannerFile->move(public_path('assets/app/upload/b'), uniqid($filePrefix).'.'.$bannerFile->getClientOriginalExtension());
        $path = $bannerFile->getPath();
        $bannerFile = str_replace(public_path(), '', $path);

        $banner = BannerMapper::addForTwitcher($user, $this->user, $bannerType, $bannerFile, $limit);

        return Redirect::to('/user/client')->with(['success' => 'We sent your banner for twitcher\'s review']);
    }
}
