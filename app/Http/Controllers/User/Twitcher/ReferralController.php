<?php

namespace App\Http\Controllers\User\Twitcher;

use Illuminate\Http\Request;
use App\Models\Referral;
use App\Models\Mappers\ReferralMapper;
use Redirect;

class ReferralController extends Controller
{

    public function index() {
        $referrals = ReferralMapper::referrals($this->user);

        return view('app.pages.user.twitcher.referral.index', compact('referrals'));
    }

    public function log() {
        $payments = ReferralMapper::referrerLog($this->user);

        return view('app.pages.user.twitcher.referral.log', compact('payments'));
    }

}
