<?php

namespace App\Http\Controllers\User\Client;

use Auth;
use App\Models\Mappers\UserMapper;
use App\Models\Mappers\RefMapper;
use Input;

class SearchController extends Controller
{
    public function index()
    {
        $languageRefs = RefMapper::type('language');
        $bannerTypeRefs = RefMapper::type('banner_type');
        $gameRefs = RefMapper::type('game');

        $languages = Input::get('languages', []);
        $bannerTypes = Input::get('banner_types', []);
        $games = Input::get('games', []);
        if (!is_array($bannerTypes)) {
            $bannerTypes = [$bannerTypes];
        }
        if (!is_array($games)) {
            $games = [$games];
        }
        if (!is_array($languages)) {
            $languages = [$languages];
        }
        $followers = intval(Input::get('followers', 0));
        $views = intval(Input::get('views', 0));
        $videos = intval(Input::get('videos', 0));

        $filters = [];
        if (count($bannerTypes) > 0) {
            $filters['banner_types'] = $bannerTypes;
        }
        if (count($games) > 0) {
            $filters['games'] = $games;
        }
        if (count($languages) > 0) {
            $filters['languages'] = $languages;
        }
        if ($followers > 0) {
            $filters['followers'] = $followers;
        }
        if ($views > 0) {
            $filters['views'] = $views;
        }
        if ($videos > 0) {
            $filters['videos'] = $videos;
        }
        $twitchers = UserMapper::findTwitchers($filters);
        dd($twitchers);

        return view('app.pages.user.client.search.index', compact('twitchers', 'filters', 'languageRefs', 'bannerTypeRefs', 'gameRefs'));
    }
}
