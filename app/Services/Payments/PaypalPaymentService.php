<?php

namespace App\Services\Payments;

use Config;
use Input;
use Session;
use App\Models\User;

//PayPal
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalPaymentService
{
    /**
     * @var ApiContext
     */
    public $apiContext;

    public $currency = 'USD';
    public $config = [];

    public function __construct() {
        $clientId = Config::get('services.paypal.client_id');
        $clientSecret = Config::get('services.paypal.client_secret');

        $this->currency = Config::get('services.paypal.currency');
        $this->config = Config::get('services.paypal.config');

        $this->redirectSuccess = Config::get('services.paypal.redirect_success');
        $this->redirectFail = Config::get('services.paypal.redirect_fail');

        $this->apiContext = $this->getApiContext($clientId, $clientSecret);
    }

    public function getApiContext($clientId, $clientSecret)
    {
        $oauthCredentials = new OAuthTokenCredential(
            $clientId,
            $clientSecret
        );
        $apiContext = new ApiContext($oauthCredentials);

        $apiContext->setConfig($this->config);
        return $apiContext;
    }

    public function getRefillUrl($amount, User $user)
    {
        $paymentTitle = Config::get('services.paypal.payment_title');

        $apiContext = $this->apiContext;
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item1 = new Item();
        $item1->setName($paymentTitle)
            ->setCurrency($this->currency)
            ->setQuantity(1)
            ->setSku(1)
            ->setPrice($amount);
        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($paymentTitle)
            ->setInvoiceNumber($user->id.'_'.date('YmdHis'));

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->redirectSuccess.'/'.$user->id)
            ->setCancelUrl($this->redirectFail.'/'.$user->id);

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        $payment->create($apiContext);

        $this->storePaymentSession($payment->getId(), $user->id, $amount);

        return $payment->getApprovalLink();
    }

    public function storePaymentSession($paymentId, $userId, $amount)
    {
        Session::set('paypal.payment', ['id' => $paymentId, 'userId' => $userId, 'amount' => $amount]);
    }

    public function checkPaymentSession($paymentId, User $user)
    {
        $paymentInfo = Session::get('paypal.payment');
        if ($paymentId == $paymentInfo['id']) {
            throw new \Exception('Payment id mismatch');
        }
        $payment = Payment::get($paymentId, $this->apiContext);
        $transactions = $payment->getTransactions();
        $transaction = $transactions[0];
        list($userId, $date) = explode('_', $transaction->invoice_number);
        if ($transaction->amount->total != $paymentInfo['amount'] || $transaction->amount->currency != $this->currency) {
            throw new \Exception('Payment amount mismatch');
        }
        if ($userId != $user->id) {
            throw new \Exception('User Id mismatch');
        }
        Session::remove('paypal.payment');

        return true;
    }

}
