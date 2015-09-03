<?php

namespace App\Http\Controllers\User\Client;

use Auth;

class ProfileController extends Controller
{
    public function index() {

        $user = $this->user;
        $profile = $user->profile;

        return view('app.pages.user.client.profile.index', compact('user', 'profile'));

    }
}
