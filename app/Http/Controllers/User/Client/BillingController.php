<?php

namespace App\Http\Controllers\User\Client;

use App\Models\UserPayment;
use Illuminate\Http\Request;
use App\Services\Payments\PaypalPaymentService;
use App\Models\Mappers\LogMapper;
use Redirect;
use Input;
use Session;
use App\Models\Mappers\PaymentMapper;
use App\Services\Payments\StripePaymentService;

class BillingController extends Controller
{
    public function index()
    {
        $cardholderName = trim($this->user->profile->first_name.' '.$this->user->profile->last_name);
        if (strpos($cardholderName, '@') !== -1) {
           $cardholderName = '';
        }
        return view('app.pages.user.client.billing.index', compact('cardholderName'));
    }

    public function save(Request $request)
    {
        $rules = [
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
        ];
        $this->validate($request, $rules);
        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');
        $user = $this->user;
        $profile = $user->profile;
        $profile->first_name = $firstName;
        $profile->last_name = $lastName;
        $profile->save();

        return redirect('/user/client/profile');
    }

    public function paypal(Request $request)
    {
        $rules = [
            'amount' => 'required|numeric|min:5|max:1000',
        ];
        $this->validate($request, $rules);
        $amount = $request->get('amount');
        $paypalService = new PaypalPaymentService();
        try {
            $url = $paypalService->getRefillUrl($amount, $this->user);
        } catch (\Exception $e) {
            LogMapper::log('paypal_error', $e->getMessage(), 'get_url', ['user' => $this->user->id, 'amount' => $amount, 'error_data' => $e->getData()]);

            return Redirect::back()->withErrors(['paypal' => 'Failed paypal']);
        }

        return redirect($url);
    }

    public function paypalSuccess($userId) {
        $paypalService = new PaypalPaymentService();
        $paymentId = Input::get('paymentId');
        $payerID = Input::get('PayerID');
        $token = Input::get('token');
        try {
            $paypalService->checkPaymentSession($paymentId, $this->user);
            $paymentInfo = $paypalService->paymentInfo;
            $amount = $paymentInfo['amount'];
            LogMapper::log('paypal_success', $amount, $userId, $paymentInfo);
        } catch (\Exception $e) {
            LogMapper::log('paypal_error', $e->getMessage(), 'success_callback', ['user' => $this->user->id, 'paymentId' => $paymentId]);

            return Redirect::to('/user/client/billing')->withErrors(['paypal' => 'Failed paypal']);
        }
        $payment = PaymentMapper::refillFromPaypal($this->user, $paypalService);
        LogMapper::log('payment', $amount, 'refilled', ['payment_id' => $payment->id, 'merchant' => 'paypal']);

        return Redirect::to('/user/client/billing')->with(['success' => 'We got your payment']);
    }

    public function paypalFail($userId) {
        LogMapper::log('paypal_error', 'Paypal failed', 'fail', ['user' => $userId, 'session' => Session::get('paypal.payment')]);
        Session::remove('paypal.payment');

        return Redirect::to('/user/client/billing')->withErrors(['paypal' => 'Failed paypal']);
    }

    public function stripe(Request $request)
    {
        $rules = [
            'amount' => 'required|numeric|min:5|max:1000',
            'card_number' => 'required|digits_between:6,21',
            'card_holder' => 'required|min:2|max:250',
            'card_year' => 'required|numeric|min:1900|max:'.(date('Y') + 10),
            'card_month' => 'required|numeric|min:1|max:12',
            'card_cvc' => 'required|numeric|min:100|max:999',
        ];
        $this->validate($request, $rules);
        $amount = $request->get('amount');
        $card_number = $request->get('card_number');
        $card_holder = $request->get('card_holder');
        $card_year = $request->get('card_year');
        $card_month = $request->get('card_month');
        $card_cvc = $request->get('card_cvc');
        $stripeService = new StripePaymentService();
        $card = [
            'number' => $card_number,
            'exp_month' => $card_month,
            'exp_year' => $card_year,
            'cvc' => $card_cvc,
            'name' => $card_holder
        ];

        try {
            $charge = $stripeService->refill($card, $amount, $this->user);
            LogMapper::log('stripe_success', $amount, $this->user->id, ['response' => $stripeService->response]);
        } catch (\Exception $e) {
            LogMapper::log('stripe_error', $e->getMessage(), 'stripe_card', ['user' => $this->user->id, 'card' => $card, 'amount' => $amount]);

            return Redirect::to('/user/client/billing')->withErrors(['stripe' => 'Failed payment']);
        }
        $payment = PaymentMapper::refillFromStripe($this->user, $stripeService);
        LogMapper::log('payment', $amount, 'refilled', ['payment_id' => $payment->id, 'merchant' => 'stripe']);

        return Redirect::to('/user/client/billing')->with(['success' => 'We got your payment']);
    }

    public function log()
    {
        $payments = PaymentMapper::payments($this->user);

        return view('app.pages.user.client.billing.log', compact('payments'));
    }

    public function transfers()
    {
        $transfers = PaymentMapper::transfers($this->user);

        return view('app.pages.user.client.billing.transfers', compact('transfers'));
    }

}
