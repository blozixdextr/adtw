<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Auth;
use Redirect;

abstract class Controller extends BaseController
{
    protected $user;

    public function __construct() {
        $this->user = Auth::user();
        if ($this->user->role != 'admin') {
            return Redirect::to('/');
        }
        view()->share('user', $this->user);
        view()->share('profile', $this->user->profile);
    }
}
