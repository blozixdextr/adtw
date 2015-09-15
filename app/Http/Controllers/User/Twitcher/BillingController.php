<?php

namespace App\Http\Controllers\User\Twitcher;

use App\Models\Mappers\NotificationMapper;
use App\Models\UserPayment;
use Illuminate\Http\Request;
use App\Services\Payments\PaypalPaymentService;
use App\Models\Mappers\LogMapper;
use Redirect;
use Input;
use Session;
use App\Models\Mappers\PaymentMapper;
use App\Services\Payments\StripePaymentService;
use Config;

class BillingController extends Controller
{
    public function index() {
        $withdrawalShare = Config::get('banner.withdrawal_share');

        return view('app.pages.user.twitcher.billing.index', compact('withdrawalShare'));
    }

    public function log() {

    }

    public function transfers() {

    }

    public function withdraw(Request $request) {
        $rules = [
            'amount' => 'required|numeric|min:1|max:1000',
            'account' => 'required|email'
        ];
        $this->validate($request, $rules);
        $amount = floatval($request->get('amount'));
        $account = $request->get('account');

        PaymentMapper::withdrawPaypalPrepare($this->user, $account, $amount);

        NotificationMapper::notify($this->user, 'You required withdrawal '.$amount);
        LogMapper::log('withdraw', $this->user->id, 'request', ['account' => $account, 'merchant' => 'paypal', 'amount' => $amount]);

        return redirect('/user/twitcher/billing')->with(['success' => 'You required withdrawal']);

    }

}
