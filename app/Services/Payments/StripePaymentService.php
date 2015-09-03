<?php

namespace App\Services\Payments;

use Config;
use Input;
use Session;

use Stripe\Stripe;
use Stripe\Charge;
use App\Models\User;

class StripePaymentService
{
    public $currency = 'USD';
    public $config = [];

    public function __construct() {
        Stripe::setApiKey(Config::get('services.stripe.key'));
    }

    public function refill($card, $amount, User $user)
    {
        //$card = ['number' => '4242424242424242', 'exp_month' => 10, 'exp_year' => 2015, 'cvc' => 333, 'name' => 'Test Tester'];

        $charge = Charge::create([
            'card' => $card,
            'amount' => $amount,
            'currency' => 'usd',
            'description' => Config::get('services.stripe.payment_title'),
            'metadata' => ['user' => $user->id]
        ]);

        return $charge;
    }

}
