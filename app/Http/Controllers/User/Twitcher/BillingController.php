<?php

namespace App\Http\Controllers\User\Twitcher;

use App\Models\Mappers\NotificationMapper;
use App\Models\UserPayment;
use Illuminate\Http\Request;
use App\Models\Mappers\LogMapper;
use App\Models\Mappers\PaymentMapper;
use Config;

class BillingController extends Controller
{
    public function index() {
        $withdrawalShare = Config::get('banner.withdrawal_share');

        return view('app.pages.user.twitcher.billing.index', compact('withdrawalShare'));
    }

    public function log() {
        $withdrawals = PaymentMapper::withdrawal($this->user);

        return view('app.pages.user.twitcher.billing.log', compact('withdrawals'));
    }

    public function transfers() {
        $transfers = PaymentMapper::transfersTwitcher($this->user);

        return view('app.pages.user.twitcher.billing.transfers', compact('transfers'));
    }

    public function withdraw(Request $request) {
        $rules = [
            'amount' => 'required|numeric|min:1|max:1000',
            'account' => 'required|email'
        ];
        $this->validate($request, $rules);
        $amount = floatval($request->get('amount'));
        if ($amount > $this->user->availableBalance()) {
            return redirect('/user/twitcher/billing')->withErrors(['amount' => 'You have no such money']);
        }
        $account = $request->get('account');

        PaymentMapper::withdrawPaypalPrepare($this->user, $account, $amount);

        NotificationMapper::notify($this->user, 'You required withdrawal '.$amount);
        LogMapper::log('withdraw', $this->user->id, 'request', ['account' => $account, 'merchant' => 'paypal', 'amount' => $amount]);

        return redirect('/user/twitcher/billing')->with(['success' => 'You required withdrawal']);

    }

}
