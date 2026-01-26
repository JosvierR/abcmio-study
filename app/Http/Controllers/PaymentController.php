<?php

namespace App\Http\Controllers;

use App\Credit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;

/** All Paypal Details class **/

use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Redirect;
use Session;
use URL;

use App\Order;


class PaymentController extends Controller
{
    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }


    public function payform($locale, Credit $credit = null)
    {
        $this->setSectionName('Método de pago');
        return view('frontend.payments.buy', compact('credit'))->with($this->get_content_site(null, null, null , ['credit' => $credit]));
    }


    public function payWithpaypal($locale, Request $request)
    {
        $credit = Credit::findOrFail($request->credit);
        if (floatval($request->amount) !== floatval($credit->price)) {
            abort(404);
        }

        $user = auth()->user();
        $order = new Order();
        $order->price = $credit->price;
        $order->credit_id = $credit->id;
        $order->total_credit = $credit->total;
        $order->description = $credit->name;
        $order->status = "pending";

        $order->user_id = $user->id;
        $order->save();

        \Session::put('orderId', $order->id);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName($credit->name)/** item name **/
        ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($request->get('amount'));
        /** unit price **/

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($request->get('amount'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Compra de Créditos:  ' . $credit->name);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('paypal.status', app()->getLocale()))/** Specify return URL **/
        ->setCancelUrl(URL::route('paypal.status', app()->getLocale()));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        /** dd($payment->create($this->_api_context));exit; **/
        try {
            $order->status = "processing";
            $order->save();

            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                \Session::put('error', 'Connection timeout');
                return Redirect::route('paypal.pay', app()->getLocale());
            } else {
                \Session::put('error', 'Some error occur, sorry for inconvenient');
                return Redirect::route('paypal.pay', app()->getLocale());
            }
        }
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }
        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            /** redirect to paypal **/
            return Redirect::away($redirect_url);
        }
        \Session::put('error', 'Unknown error occurred');
        return Redirect::route('paywithpaypal', app()->getLocale());
    }

    public function getPaymentStatus($locale, Request $request)
    {
        if (!$request->has('PayerID')) {
//            \Session::put('error', 'Hubo un error al procesar su pago, revise su cuenta de paypal por favor.');
            return Redirect::route('home',
                app()->getLocale())->withErrors('Hubo un error al procesar su pago, revise su cuenta de paypal por favor.');
        }
//        http://127.0.0.1:8000/paypal/status?paymentId=PAYID-LYR2UGQ4Y432375YA507202G&token=EC-47598101XB951002Y&PayerID=M2UYDT9A89BUW
//        WHEN CANCEL URL: https://abcmio.com/paypal/status?token=EC-4GE72814HU448091T
        /** Get the payment ID before session clear **/

        $payment_id = Session::get('paypal_payment_id');
        $orderId = Session::get('orderId');
        $order = Order::findOrFail($orderId);

        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (!$request->has('PayerID') || !$request->has('token')) {
//            \Session::put('error', 'Hubo un error en el proceso.');
            return Redirect::route('home')->withErrors('Hubo un error en el proceso.');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);
        if ($result->getState() == 'approved') {
            \Session::put('success', 'Pago completado !!!');
            $order->status = "completed";
            $order->save();

            $user = auth()->user();
            $total = (int)$user->credits + (int)$order->total_credit;
            $user->credits = $total;
            $user->save();

            return Redirect::route('home');
        }
        \Session::put('error', 'Error en completar el pago');
        return Redirect::route('home')->withErrors('Error en completar el pago');
    }

}
