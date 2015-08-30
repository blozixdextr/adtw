<?php

namespace App\Http\Controllers\User\Client;

use Auth;

class IndexController extends Controller
{
    public function index() {

        dd(Auth::user());

    }
}
