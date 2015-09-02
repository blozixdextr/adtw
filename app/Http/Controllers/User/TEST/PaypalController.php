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

//PayPal
use App\Services\Payments\PaypalPaymentService;


class PaypalController extends Controller
{

    public function test()
    {
        $paypalService = new PaypalPaymentService();
        $task = new \stdClass();
        $task->id = 12;
        $task->title = 'Task example title';
        $task->total = 50.25;

        try {
            $url = $paypalService->getPayTaskUrl($task);
        } catch (\Exception $e) {
            Log::error('PayPal Error '.$e->getMessage());
            abort(404);
            return false;
        }

        echo $url;
        die();
    }

    public function success($taskId) {
        $paypalService = new PaypalPaymentService();
        $paymentId = Input::get('paymentId');
        $payerID = Input::get('PayerID');
        $token = Input::get('token');
        $task = new \stdClass();
        $task->id = $taskId;
        $task->title = 'Task example title';
        $task->total = 50.25;
        try {
            $paypalService->checkPaymentSession($paymentId, $task);
        } catch (\Exception $e) {
            Log::error('PayPal Error '.$e->getMessage());
            abort(404);
            return false;
        }
        // set task paid here
        dd($paymentId, $payerID, $token);
    }

    public function fail($taskId) {
        Log::error('PayPal Declined for task '.$taskId);
        return redirect('/dashboard');
    }

}
