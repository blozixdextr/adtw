<?php

namespace App\Services\Payments;

use Config;


class Amount
{
    public $task;
    public $tax;
    public $total;
    public $amount;
    public $config;

    public function __construct($task) {
        $this->task = $task;
        $this->config = Config::get('payments');

    }

    public function calc($sum) {
        if ($sum < 200) {
            $commissionShare = 0.15;
        } else {
            $commissionShare = 0.1;
        }
        $commission = $commissionShare * $sum;
        $this->tax = $commission * $this->config['tax'];
        $this->total =  $sum + $this->tax;
    }

}