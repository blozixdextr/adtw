<?php

namespace App\Http\Controllers\User;

use Auth;
use App\Models\User;

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
        $user = Auth::user();
        $userView = User::findOrFail($userId);
        if ($userView->role == 'admin') {
            return redirect('/');
        }
        if ($userView->is_active == 0) {
            return redirect('/');
        }
        $profileView = $userView->profile;

        $layout = 'index';
        if ($user) {
            if ($user->type == 'twitcher') {
                $layout = 'twitcher';
            }
            if ($user->type == 'client') {
                $layout = 'client';
            }
        }

        return view('app.pages.user.profile', compact('userView', 'profileView', 'user', 'layout'));
    }
}
