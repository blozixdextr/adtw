<?php

namespace App\Http\Controllers\User\Client;

use App\Http\Controllers\Controller as BaseController;
use Auth;

abstract class Controller extends BaseController
{
    protected $user;

    public function __construct() {
        $this->user = Auth::user();
        view()->share('user', $this->user);
        view()->share('profile', $this->user->profile);
    }
}
