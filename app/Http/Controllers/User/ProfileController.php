<?php

namespace App\Http\Controllers\User;

use Auth;

class ProfileController extends Controller
{
    public function index() {
        $user = Auth::user();
        if ($user->role == 'twitcher') {
            return redirect('/user/twitcher');
        }
        if ($user->role == 'client') {
            return redirect('/user/client');
        }
        if ($user->role == 'admin') {
            return redirect('/admin');
        }

        return redirect('/');
    }

    public function user($userId) {

    }
}
