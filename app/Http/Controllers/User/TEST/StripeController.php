<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App;
use Auth;
use Redirect;
use Session;
use App\Models\User;
use Input;
use Log;


use App\Services\Payments\StripePaymentService;


class StripeController extends Controller
{

    public function test()
    {
        $stripeService = new StripePaymentService();
        $task = new \stdClass();
        $task->id = 12;
        $task->title = 'Task example title';
        $task->total = 50.25;

        $card = ['number' => '4242424242424242', 'exp_month' => 10, 'exp_year' => 2015, 'cvc' => 333, 'name' => 'Test Tester'];

        try {
            $charge = $stripeService->payTask($task, $card);
            dd($charge);
        } catch (\Exception $e) {
            Log::error('Stripe Error '.$e->getMessage());
            abort(404);
            return false;
        }
    }
}
