<?php

namespace App\Services\Payments;

use Config;
use Input;
use Session;

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

    public $currency = 'EUR';
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

    public function getPayTaskUrl($task)
    {
        $apiContext = $this->apiContext;
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item1 = new Item();
        $item1->setName($task->title)
            ->setCurrency($this->currency)
            ->setQuantity(1)
            ->setSku($task->id)
            ->setPrice($task->total);
        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        $amount = new Amount();
        $amount->setCurrency($this->currency)
            ->setTotal($task->total);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Payment for ".$task->title)
            ->setInvoiceNumber($task->id.'_'.date('YmdHis'));

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->redirectSuccess.'/'.$task->id)
            ->setCancelUrl($this->redirectFail.'/'.$task->id);

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        $payment->create($apiContext);

        $this->storePaymentSession($payment->getId(), $task->id);

        return $payment->getApprovalLink();
    }

    public function storePaymentSession($paymentId, $taskId)
    {
        Session::set('paypal.payment', ['id' => $paymentId, 'taskId' => $taskId]);
    }

    public function checkPaymentSession($paymentId, $task)
    {
        $paymentInfo = Session::get('paypal.payment');
        if ($paymentId == $paymentInfo['id']) {
            throw new \Exception('Payment id mismatch');
        }
        $payment = Payment::get($paymentId, $this->apiContext);
        $transactions = $payment->getTransactions();
        $transaction = $transactions[0];
        list($taskId, $date) = explode('_', $transaction->invoice_number);

        if ($transaction->amount->total != $task->total || $transaction->amount->currency != $this->currency) {
            throw new \Exception('Payment amount mismatch');
        }

        if ($taskId != $task->id) {
            throw new \Exception('Task Id mismatch');
        }

        // @todo: Maybe check date here

        Session::remove('paypal.payment');

        return true;
    }

}
