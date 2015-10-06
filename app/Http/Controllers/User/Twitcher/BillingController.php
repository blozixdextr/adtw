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
    public function index()
    {
        return view('app.pages.user.twitcher.billing.index');
    }

    public function log()
    {
        $withdrawals = PaymentMapper::withdrawal($this->user);

        return view('app.pages.user.twitcher.billing.log', compact('withdrawals'));
    }

    public function transfers()
    {
        $transfers = PaymentMapper::transfersTwitcher($this->user);

        return view('app.pages.user.twitcher.billing.transfers', compact('transfers'));
    }

    public function withdraw(Request $request)
    {
        // todo: check for coupons and change min
        $rules = [
            'amount' => 'required|numeric|min:50|max:1000',
            'account' => 'required|email'
        ];
        $this->validate($request, $rules);
        $amount = floatval($request->get('amount'));
        if ($amount > $this->user->availableBalance()) {
            return redirect('/user/twitcher/billing')->withErrors(['amount' => 'You have no such money']);
        }
        $account = $request->get('account');
        // todo: set coupon as used and canceled
        PaymentMapper::withdrawPaypalPrepare($this->user, $account, $amount);
        NotificationMapper::withdraw($this->user, $amount, 'USD', 'paypal', $account);
        LogMapper::log('withdraw', $this->user->id, 'request', ['account' => $account, 'merchant' => 'paypal', 'amount' => $amount]);

        return redirect('/user/twitcher/billing')->with(['success' => 'You required withdrawal']);

    }

    public function coupon(Request $request)
    {
        $rules = [
            'coupon' => 'required|min:3|max:25'
        ];
        $this->validate($request, $rules);
        $coupon = $request->get('coupon');

    }

}
