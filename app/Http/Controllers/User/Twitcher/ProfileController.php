<?php

namespace App\Http\Controllers\User\Twitcher;

use App\Http\Requests\Request;
use Auth;
use App\Models\Mappers\LogMapper;
use App\Apis\Twitch;

class ProfileController extends Controller
{
    public function index() {
        return view('app.pages.user.twitcher.profile.index');
    }

    public function save(Request $request) {
        $rules = [
            'language' => 'required|alpha|min:2|max:3'
        ];
        $this->validate($request, $rules);

    }
}
