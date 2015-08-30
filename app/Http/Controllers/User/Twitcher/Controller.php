<?php

namespace App\Http\Controllers\User\Twitcher;

use App\Http\Controllers\Controller as BaseController;
use Auth;

abstract class Controller extends BaseController
{
    protected $user;

    public function __construct() {
        $this->user = Auth::user();
    }
}
