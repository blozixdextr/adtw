<?php

namespace App\Http\Controllers\User;

use Auth;

class UserController extends Controller
{
    public function index() {
        $user = Auth::user();

    }
}
