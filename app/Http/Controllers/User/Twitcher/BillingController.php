<?php

namespace App\Http\Controllers\User\Twitcher;

use App\Models\Mappers\CouponMapper;
use App\Models\Mappers\NotificationMapper;
use App\Models\UserPayment;
use Illuminate\Http\Request;
use App\Models\Mappers\LogMapper;
use App\Models\Mappers\PaymentMapper;
use Config;
use Redirect;

class BillingController extends Controller
{
    public function index()
    {
        $minWithdraw = PaymentMapper::getMinWithdraw($this->user);
        $blockWithdraw = false;
        $withdrawMessage = '';
        if ($this->user->availableBalance() < $minWithdraw) {
            $blockWithdraw = true;
            $withdrawMessage = 'Minimum withdrawal sum is $'.$minWithdraw;
        }
        $coupon = CouponMapper::byCode('ADD25USD');
        if ($coupon) {
            if (CouponMapper::usedByUser($this->user, $coupon)) {
                if (!CouponMapper::paidByUser($this->user, $coupon)) {
                    $withdrawMessage .= '<br>'.$coupon->subtitle;
                }
            }
        }

        return view('app.pages.user.twitcher.billing.index', compact('minWithdraw', 'withdrawMessage', 'blockWithdraw'));
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

    public function getMinWithdraw($user)
    {
        $min = 50;
        $coupon = CouponMapper::byCode('ADD25USD');
        if ($coupon) {
            if (CouponMapper::usedByUser($user, $coupon)) {
                $min = $min + 25;
            }
        }

        return $min;
    }

    public function withdraw(Request $request)
    {
        $min = PaymentMapper::getMinWithdraw($this->user);
        $rules = [
            'amount' => 'required|numeric|min:'.$min.'|max:1000',
            'account' => 'required|email'
        ];
        $this->validate($request, $rules);
        $amount = floatval($request->get('amount'));
        if ($amount > $this->user->availableBalance()) {
            return redirect('/user/twitcher/billing')->withErrors(['amount' => 'You have no such money']);
        }
        $account = $request->get('account');
        PaymentMapper::withdrawPaypalPrepare($this->user, $account, $amount);
        NotificationMapper::withdraw($this->user, $amount, 'USD', 'paypal', $account);
        LogMapper::log('withdraw', $this->user->id, 'request', ['account' => $account, 'merchant' => 'paypal', 'amount' => $amount]);

        $coupon = CouponMapper::byCode('ADD25USD');
        if ($coupon) {
            if (!CouponMapper::paidByUser($this->user, $coupon)) {
                CouponMapper::pay($this->user, $coupon);
            }
        }

        return redirect('/user/twitcher/billing')->with(['success' => 'You required withdrawal']);

    }

    public function coupon(Request $request)
    {
        $rules = [
            'coupon' => 'required|min:3|max:25'
        ];
        $this->validate($request, $rules);
        $couponCode = $request->get('coupon');
        $coupon = CouponMapper::byCode($couponCode);
        if (!$coupon) {
            return Redirect::back()->withErrors(['coupon' => 'This coupon is not valid'])->withInput(['coupon' => $couponCode]);
        }
        if (CouponMapper::usedByUser($this->user, $coupon)) {
            return Redirect::back()->withErrors(['coupon' => 'You already have used this coupon'])->withInput(['coupon' => $couponCode]);;
        }
        switch ($coupon->code) {
            case 'ADD25USD':
                CouponMapper::track($this->user, $coupon);
                $this->user->balance = $this->user->balance + 25;
                $this->user->save();
                NotificationMapper::coupon($this->user, $coupon);
                LogMapper::log('coupon', $coupon->code, $this->user->id);

                return Redirect::back()->with(['success' => $coupon->title]);
                break;
        }

        return Redirect::back()->withErrors(['coupon' => 'Coupon is not ready yet']);

    }

}
