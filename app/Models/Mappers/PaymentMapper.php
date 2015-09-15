<?php

namespace App\Models\Mappers;

use App\Models\User;
use App\Models\UserProfile ;
use App\Models\UserPayment;
use App\Models\UserTransfer;
use App\Services\Payments\PaypalPaymentService;
use App\Services\Payments\StripePaymentService;
use App\Models\Withdrawal;


class PaymentMapper
{

    public static function refillFromPaypal(User $user, PaypalPaymentService $paypalService)
    {
        $amount = floatval($paypalService->paymentInfo['amount']);
        $currency = $paypalService->paymentInfo['currency'];
        $transaction_number = $paypalService->paymentInfo['id'];
        $cart = $paypalService->paymentInfo['cart'];
        $response = $paypalService->response;

        $payment = UserPayment::create([
            'user_id' => $user->id,
            'merchant' => 'paypal',
            'transaction_number' => $transaction_number,
            'title' => 'refill',
            'amount' => $amount,
            'currency' => $currency,
            'response' => $response,
            'cart' => $cart
        ]);

        $balance = $user->balance;
        $user->balance = $balance + $amount;
        $user->save();

        return $payment;
    }

    public static function refillFromStripe(User $user, StripePaymentService $stripeService)
    {
        $amount = floatval($stripeService->response->amount / 100);
        $currency = $stripeService->response->currency;
        $transaction_number = $stripeService->response->id;
        $cart = [$stripeService->response->description];
        $response = $stripeService->response->__toArray();

        $payment = UserPayment::create([
            'user_id' => $user->id,
            'merchant' => 'stripe',
            'transaction_number' => $transaction_number,
            'title' => 'refill',
            'amount' => $amount,
            'currency' => $currency,
            'response' => $response,
            'cart' => $cart
        ]);

        $balance = $user->balance;
        $user->balance = $balance + $amount;
        $user->save();

        return $payment;
    }

    public static function payments(User $user, $limit = 50)
    {
        return UserPayment::whereUserId($user->id)->paginate($limit);
    }

    public static function transfers(User $user, $limit = 50)
    {
        return UserTransfer::whereBuyerId($user->id)->paginate($limit);
    }

    public static function transfersTwitcher(User $user, $limit = 50)
    {
        return UserTransfer::whereSellerId($user->id)->paginate($limit);
    }

    public static function withdrawPaypalPrepare(User $user, $paypalEmail, $amount)
    {
        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'merchant' => 'paypal',
            'account' => $paypalEmail,
            'amount' => $amount,
            'currency' => 'USD',
            'status' => 'waiting',
            'transaction_number' => ''
        ]);

        $user->balance_blocked = $user->balance_blocked + $amount;
        $user->save();

        return $withdrawal;
    }

    public static function withdrawal(User $user, $limit = 50)
    {
        return Withdrawal::whereUserId($user->id)->paginate($limit);
    }

}