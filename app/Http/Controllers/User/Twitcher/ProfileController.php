<?php

namespace App\Http\Controllers\User\Twitcher;

use Illuminate\Http\Request;
use Auth;
use App\Models\Mappers\LogMapper;
use App\Models\Mappers\RefMapper;
use App\Models\Ref;
use App\Apis\Twitch;
use Redirect;

class ProfileController extends Controller
{

    public function index(Request $request) {
        $languages = RefMapper::type('language');
        $bannerTypes = RefMapper::type('banner_type');
        $games = RefMapper::type('game');

        $user = $this->user;

        if ($user->language_id == 0) {
            $userAgentLanguage = $request->server('HTTP_ACCEPT_LANGUAGE');
            $user->language_id = 4;
            if (strpos($userAgentLanguage, 'ru') === 0) {
                $user->language_id = 5;
            }
            if (strpos($userAgentLanguage, 'es') === 0) {
                $user->language_id = 6;
            }
            $user->save();
        }

        if (count($user->bannerTypes()->get()) == 0) {
            foreach ($bannerTypes as $bannerType) {
                $user->refs()->attach($bannerType->id);
            }
            $user->save();
        }

        return view('app.pages.user.twitcher.profile.index', compact('languages', 'bannerTypes', 'games', 'user'));
    }

    public function save(Request $request) {
        $rules = [
            'language' => 'required|numeric|exists:refs,id',
            'banner_types' => 'required|array',
            'games' => 'required|array',
        ];

        $this->validate($request, $rules);
        $language = $request->get('language');
        $language = Ref::find($language);
        if ($language && $language->type == 'language') {
            $this->user->language_id = $language->id;
            $this->user->save();
        } else {
            return Redirect::back()->withErrors(['language' => 'You must select your language']);
        }

        $bannerTypes = $request->get('banner_types');
        $bannersClean = [];
        foreach ($bannerTypes as $b) {
            $bannerType = Ref::find($b);
            if ($bannerType && $bannerType->type == 'banner_type') {
                $bannersClean[] = $b;
            }
        }
        if (count($bannersClean) == 0) {
            return Redirect::back()->withErrors(['banner_types' => 'You must select allowed banner types']);
        }

        $games = $request->get('games');
        $gamesClean = [];
        foreach ($games as $b) {
            $game = Ref::find($b);
            if ($game && $game->type == 'game') {
                $gamesClean[] = $b;
                if ($game->pid > 0) {
                    $gamesClean[] = $game->parent->id;
                }
            }
        }
        if (count($gamesClean) == 0) {
            return Redirect::back()->withErrors(['games' => 'You must select games you play']);
        }
        $gamesClean = array_unique($gamesClean);

        $this->user->refs()->sync(array_merge($bannersClean, $gamesClean));

        return redirect('/user/twitcher/profile');
    }
}
