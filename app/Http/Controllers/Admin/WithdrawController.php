<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mappers\LogMapper;
use App\Models\Mappers\NotificationMapper;
use Redirect;
use Config;
use Illuminate\Http\Request;
use App\Models\Withdrawal;
use App\Services\Payments\PaypalPaymentService;

class WithdrawController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::whereStatus('waiting')->paginate(50);

        return view('admin.pages.withdraw.index', compact('withdrawals'));
    }

    public function all()
    {
        $withdrawals = Withdrawal::paginate(50);

        return view('admin.pages.withdraw.all', compact('withdrawals'));
    }

    public function show($withdrawId)
    {
        $withdrawal = Withdrawal::findOrFail($withdrawId);

        return view('admin.pages.withdraw.show', compact('withdrawal'));
    }

    public function accept($withdrawId)
    {
        $withdrawal = Withdrawal::findOrFail($withdrawId);
        $paypal = new PaypalPaymentService;
        try {
            $withdrawShare = (100 - Config::get('banner.withdrawal_share'))/100;
            $amount = $withdrawal->amount * $withdrawShare;
            $result = $paypal->payout($withdrawal->user, $amount, $withdrawal->currency, $withdrawal->account, $withdrawal->id);
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            LogMapper::log('withdraw_error', $withdrawal->id, 'paypal', ['error' => json_decode($e->getData())]);

            return redirect('/admin/withdraw')->withErrors(['Paypal withdrawal failed', $e->getData()]);
        }
        if ($result->batch_header->batch_status == 'SUCCESS') {
            $withdrawal->admin_id = $this->user->id;
            $withdrawal->status = 'done';
            $withdrawal->transaction_number = $result->batch_header->payout_batch_id;
            $withdrawal->response = $result->toArray();
            $user = $withdrawal->user;
            $user->balance_blocked = $user->balance_blocked - $withdrawal->amount;
            $user->balance = $user->balance - $withdrawal->amount;
            if ($user->balance_blocked < 0) {
                $user->balance_blocked = 0;
            }
            if ($user->balance < 0) {
                $user->balance = 0;
            }
            $withdrawal->save();
            $user->save();
            NotificationMapper::withdrawAccept($withdrawal);
            LogMapper::log('withdraw_success', $withdrawal->id, 'paypal_success', [
                'amount' => $withdrawal->amount.$withdrawal->currency,
                'withdrawal' => $amount.$withdrawal->currency,
                'response' => $result->toArray()]);

            return redirect('/admin/withdraw/'.$withdrawal->id.'/show')->with(['success' => 'Withdraw accepted']);
        } else {
            LogMapper::log('withdraw_error', $withdrawal->id, 'paypal_failed', $result->toArray());

            return redirect('/admin/withdraw')->withErrors(['Paypal withdrawal failed', json_encode($result->toArray())]);
        }
    }

    public function decline($withdrawId)
    {
        $withdrawal = Withdrawal::findOrFail($withdrawId);

        return view('admin.pages.withdraw.decline', compact('withdrawal'));
    }

    public function declineSave($withdrawId, Request $request)
    {
        $rules = ['comment' => 'required|min:5|max:255'];

        $this->validate($request, $rules);

        $withdrawal = Withdrawal::findOrFail($withdrawId);
        $withdrawal->admin_id = $this->user->id;
        $withdrawal->admin_comment = $request->get('comment');
        $withdrawal->status = 'declined';
        $user = $withdrawal->user;
        $user->balance_blocked = $user->balance_blocked - $withdrawal->amount;
        if ($user->balance_blocked < 0) {
            $user->balance_blocked = 0;
        }
        $withdrawal->save();
        $user->save();

        NotificationMapper::withdrawDecline($withdrawal);
        LogMapper::log('withdraw_decline', $withdrawal->id, $withdrawal->amount, $withdrawal->toArray());

        return redirect('/admin/withdraw/'.$withdrawal->id.'/show')->with(['success' => 'Withdraw declined']);
    }
}
