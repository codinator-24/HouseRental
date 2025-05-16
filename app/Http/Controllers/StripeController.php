<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    
    public function pay(){
        return view('payment/pay');
    }

    public function checkout(){

        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $session = \Stripe\Checkout\Session::create([
            'line_items'=>[
                [
                    'price_data'=>[
                        'currency' => 'usd',
                        'product_data'=>[
                            'name' => 'House',
                        ],
                        'unit_amount'=>500, //5.00 GBP
                    ],
                    'quantity'=>1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('success'),
                'cancel_url' => route('pay'),
            ]);
            return redirect()->away($session->url);
    }

    public function success(){

    }


}
