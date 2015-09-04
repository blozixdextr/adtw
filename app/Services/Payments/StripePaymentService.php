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
    public $response;

    public function __construct() {
        Stripe::setApiKey(Config::get('services.stripe.key'));
    }

    public function refill($card, $amount, User $user)
    {
        //$card = ['number' => '4242424242424242', 'exp_month' => 10, 'exp_year' => 2015, 'cvc' => 333, 'name' => 'Test Tester'];
        $amount = $amount * 100; // must be in cents
        $charge = Charge::create([
            'card' => $card,
            'amount' => $amount,
            'currency' => $this->currency,
            'description' => Config::get('services.stripe.payment_title'),
            'metadata' => ['user' => $user->id]
        ]);
        $this->response = $charge;

        return $this->validatePayment($charge, $amount, $user);
    }

    public function validatePayment($response, $amount, User $user)
    {
        if (!$response->paid || $response->refunded) {
            throw new \Exception('Stripe: card is not paid');
        }
        $updatedCharge = Charge::retrieve($response->id);
        if ($updatedCharge->metadata['user'] != $user->id) {
            throw new \Exception('Stripe: user id mismatch');
        }
        if ($response->amount != $amount) {
            throw new \Exception('Stripe: wrong amount');
        }
        if (strtoupper($response->currency) != $this->currency) {
            throw new \Exception('Stripe: wrong currency');
        }

        return true;
    }

}
