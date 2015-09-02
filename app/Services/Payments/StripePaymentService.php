<?php

namespace App\Services\Payments;

use Config;
use Input;
use Session;

use Stripe\Stripe;
use Stripe\Charge;

class StripePaymentService
{
    public $currency = 'USD';
    public $config = [];

    public function __construct() {
        Stripe::setApiKey(Config::get('services.stripe.key'));
    }

    public function payTask($task, $card)
    {
        /*
        $task->id = $taskId;
        $task->title = 'Task example title';
        $task->total = 50.25;
        */
        $charge = Charge::create([
            'card' => $card,
            'amount' => 2000,
            'currency' => 'usd',
            'description' => 'Payment for '.$task->title,
            'metadata' => ['task' => $task->id]
        ]);

        return $charge;
    }

}
