<?php

namespace App\Http\Controllers\User\Client;

use Auth;

class IndexController extends Controller
{
    public function index() {

        $user = $this->user;
        $profile = $user->profile;

        return view('app.pages.user.client.index', compact('user', 'profile'));

    }
}
