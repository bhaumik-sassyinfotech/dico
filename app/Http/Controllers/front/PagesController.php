<?php

namespace App\Http\Controllers\front;

namespace App\Http\Controllers\front;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class PagesController extends Controller {

    public function getOrder() {
        return view('front.order');
    }

    public function postOrder(Request $request) {
        /*
         * Validation of the $request
         */
        //return $request->all();
        $validator = \Validator::make(Input::all(), [
                    'first_name' => 'required|string|min:2|max:32',
                    'last_name' => 'required|string|min:2|max:32',
                    'email' => 'required|email',
                    'product' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // Checking is product valid
        $product = $request->input('product');
        switch ($product) {
            case 'book':
                $amount = 1000;
                break;
            case 'game':
                $amount = 2000;
                break;
            case 'movie':
                $amount = 1500;
                break;
            // default:
            // 	return redirect()->route('order')->withErrors('Product not valid!')->withInput();
        }

        //$token = $request->input('stripeToken');
        //$source     = $request->input('source');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        //$emailCheck = User::where('email_id', $email)->value('email_id');
     //   return $request->all();
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $source = \Stripe\Source::create([
                    'type' => 'card',
                    'card' => [
                        'number' => $request->input('card_number'),
                        'cvc' => $request->input('cvc'),
                        'exp_month' => $request->input('ex_month'),
                        'exp_year' => $request->input('ex_year'),
                    ],
        ]);
    //    return $sourceId;
        $sourceId = $source->id;
        // If the email doesn't exist in the database create new customer and user record
        //if (!isset($emailCheck)) {
        // Create a new Stripe customer
        try {
            $customer = \Stripe\Customer::create([
                        'source' => $sourceId,
                        'email' => $email,
                        'metadata' => [
                            "First Name" => $first_name,
                            "Last Name" => $last_name
                        ]
            ]);
        } catch (\Stripe\Error\Card $e) {
            return redirect()->route('order')->withErrors($e->getMessage())->withInput();
        }

        $customerID = $customer->id;

        $subscriptionwithstripe = \Stripe\Subscription::create(array(
                    "customer" => $customerID,
                    "items" => array(
                        array(
                            "plan" => 'plan_CHQ1UYRnYyJGEU',
                        ),
                    )
        ));
       // return $subscriptionwithstripe;
        // Create a new user in the database with Stripe
//			$user = User::create([
//					'first_name'         => $first_name,
//					'last_name'          => $last_name,
//					'email_id'           => $email,
//					'city_id'            => 1,
//					'country_id'         => 1,
//					'state_id'           => 1,
//					'user_role_id'       => 1,
//					'stripe_customer_id' => $customerID,
//				]);
        /* } else {
          $customerID = User::where('email_id', $email)->value('stripe_customer_id');
          $user       = User::where('email_id', $email)->first();
          } */

        // Charging the Customer with the selected amount
        try {
            ///$card = \Stripe\card::create($customerID, $request->tokenId); // add this to add a card.
            $charge = \Stripe\Charge::create([
                        'amount' => $amount,
                        'currency' => 'usd',
                        'customer' => $customerID,
                        'metadata' => [
                            'product_name' => $product
                        ]
            ]);
          
        } catch (\Stripe\Error\Card $e) {
            return $e->getMessage();
            // return redirect()->route('order')
            //                  ->withErrors($e->getMessage())
            // 	->withInput();
        }

        // Create purchase record in the database
//		Purchase::create([
//				'user_id'               => $user->id,
//				'product'               => $product,
//				'amount'                => $amount,
//				'stripe_transaction_id' => $charge->id,
//			]);

        return $charge;

        //return redirect()->route('en/order')->with('successful', 'Your purchase was successful!');
        return redirect('/order')->with('successful', 'order success');
    }

}
