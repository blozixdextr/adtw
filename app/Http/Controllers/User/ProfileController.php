<?php

namespace App\Http\Controllers\User;

use Auth;

class ProfileController extends Controller
{
    public function index() {
        $user = Auth::user();

        return redirect('/');
    }
}
