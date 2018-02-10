<?php

namespace App\Http\Controllers\front;

use App\Http\Requests;
use App\User;
use App\Company;
use App\CompanyPayment;
use App\EmailTemplate;
use App\Packages;
use Illuminate\Support\Facades\Redirect;
use Helpers;
use Validator;
use DB;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller {

    public function __construct(Request $request) {
        
    }

    public function index() {
        
    }

    public function frontLogin(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                        'email' => 'required|email',
                        'password' => 'required',
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
            $user = User::where('role_id', 2)->where('email', $request->input('email'))->first();
            if ($user) {
                if (Auth::guard('front')->attempt(['email' => $request->email, 'password' => $request->password])) {
                    return redirect('/companyProfile')->with('success', 'Login successfully.');
                } else {
                    return Redirect::back()->with('err_msg', 'Username and password are invalid');
                }
            } else {
                return Redirect::back()->with('err_msg', 'Username and password are invalid');
            }
        } catch (\exception $e) {
//dd($e->getMessage());
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }

    /*
     * Use the following to store data in company and user table with email
     */

    public function companyRegister(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                        'name' => 'required',
                        'company_name' => 'required',
                        'email' => 'required|email|unique:users,email',
                        'package_id' => 'required',
                        'password' => 'required',
                        'card_number' => 'required',
                        'cvc' => 'required',
                        'ex_month' => 'required',
                        'ex_year' => 'required',
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
            $companyList = Company::where('company_name', $request->company_name)->first();
            if ($companyList) {
                return Redirect::back()->with('err_msg', 'The company name has already been taken.')->withInput();
            }
            /* Stripe Payment */
            /* Get product ID from packages table */
            $package = Packages::findOrFail($request->package_id);

            /* end package table */
            $my_plan = 0;
            if ($request->package_id != 1) {
                /* Create source */
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                try {
                    $source = \Stripe\Source::create([
                                'type' => 'card',
                                'card' => [
                                    'number' => $request->card_number,
                                    'cvc' => $request->cvc,
                                    'exp_month' => $request->ex_month,
                                    'exp_year' => $request->ex_year,
                                ],
                    ]);
                } catch (\Stripe\Error\InvalidRequest $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Invalid parameters were supplied to Stripe's API
                } catch (\Stripe\Error\Authentication $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput();
// Authentication with Stripe's API failed
// (maybe you changed API keys recently)
                } catch (\Stripe\Error\ApiConnection $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Network communication with Stripe failed
                } catch (\Stripe\Error $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Display a very generic error to the user, and maybe send
// yourself an email
                } catch (Exception $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Something else happened, completely unrelated to Stripe
                }

//  return $source;
                $sourceId = $source->id;
                /* End Create source */

                /* create customer */
                try {
                    $customer = \Stripe\Customer::create([
                                'source' => $sourceId,
                                'email' => $request->email,
                                'metadata' => [
                                    "First Name" => $request->name,
                                    "Last Name" => $request->name
                                ]
                    ]);
                } catch (\Stripe\Error\InvalidRequest $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Invalid parameters were supplied to Stripe's API
                } catch (\Stripe\Error\Authentication $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput();
// Authentication with Stripe's API failed
// (maybe you changed API keys recently)
                } catch (\Stripe\Error\ApiConnection $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Network communication with Stripe failed
                } catch (\Stripe\Error $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Display a very generic error to the user, and maybe send
// yourself an email
                } catch (Exception $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Something else happened, completely unrelated to Stripe
                }
                $customerID = $customer->id;
                try {
                    $subscriptionwithstripe = \Stripe\Subscription::create(array(
                                "customer" => $customerID,
                                "items" => array(
                                    array(
                                        "plan" => $package->product_id, //this is get from database and set from stripe payment login
                                    ),
                                )
                    ));
                } catch (\Stripe\Error\InvalidRequest $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Invalid parameters were supplied to Stripe's API
                } catch (\Stripe\Error\Authentication $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput();
// Authentication with Stripe's API failed
// (maybe you changed API keys recently)
                } catch (\Stripe\Error\ApiConnection $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Network communication with Stripe failed
                } catch (\Stripe\Error $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Display a very generic error to the user, and maybe send
// yourself an email
                } catch (Exception $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Something else happened, completely unrelated to Stripe
                }
                /* End create customer */

                /* payment charge */
                try {
                    $charge = \Stripe\Charge::create([
                                'amount' => $package->amount * 100,
                                'currency' => 'usd',
                                'customer' => $customerID,
                                'metadata' => [
                                    'product_name' => $package->name
                                ]
                    ]);
                } catch (\Stripe\Error\InvalidRequest $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Invalid parameters were supplied to Stripe's API
                } catch (\Stripe\Error\Authentication $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Authentication with Stripe's API failed
// (maybe you changed API keys recently)
                } catch (\Stripe\Error\ApiConnection $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Network communication with Stripe failed
                } catch (\Stripe\Error $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Display a very generic error to the user, and maybe send
// yourself an email
                } catch (Exception $e) {
                    return Redirect::back()->with('err_msg', $e->getMessage())->withInput(); // Something else happened, completely unrelated to Stripe
                }
                /* end payment chage */
            } else {
                $my_plan = 1;
            }
            $companyData = new Company;
            $companyData->company_name = $request->company_name;
            $companyData->description = $request->company_description;
            $companyData->allow_anonymous = 0;
            $companyData->allow_add_admin = 0;
            $companyData->company_admin = 1;
            $companyData->package_name = $package->name;
            $companyData->package_amount = $package->amount;
            $companyData->package_total_user = $package->total_user;
            if ($my_plan == 0) {
                $companyData->stripe_customer_id = $customerID; //stripe customer id
            } else {
                $companyData->stripe_customer_id = null;
            }

            $companyData->save();
            $userData = new User;
            $userData->name = $request->name;
            $userData->email = $request->email;
            $userData->is_active = 1;
            $userData->role_id = 2;
            $userData->company_id = $companyData->id;
            $userData->first_login = 0;
            $userData->password = bcrypt($request->password);
            $userData->is_suspended = 0;
            if ($my_plan == 0) {
                $userData->stripe_customer_id = $customerID; //stripe customer id
                $userData->payment_expiry_date = date('Y-m-d', strtotime("+30 days")); //stripe customer payment expiry date
            } else {
                $userData->stripe_customer_id = null;
                $userData->payment_expiry_date = null;
            }

            if ($userData->save()) {
                /*                 * *****company Log******** */
                if ($my_plan == 0) {
                    $CompanyPayment = new CompanyPayment;
                    $CompanyPayment->amount = $package->amount;
                    $CompanyPayment->currency = $charge->currency;
                    $CompanyPayment->transaction_id = $charge->balance_transaction;
                    $CompanyPayment->payment_status = $charge->paid;
                    $CompanyPayment->card_brand = $charge->source->card->brand;
                    $CompanyPayment->card_country = $charge->source->card->country;
                    $CompanyPayment->card_exp_month = $charge->source->card->exp_month;
                    $CompanyPayment->card_exp_year = $charge->source->card->exp_year;
                    $CompanyPayment->status = $charge->status;
                    $CompanyPayment->company_id = $companyData->id;
                    $CompanyPayment->stripe_cust_id = $customerID;
                    $CompanyPayment->billing_start_date = date('Y-m-d');
                    $CompanyPayment->billing_end_date = date('Y-m-d', strtotime("+30 days"));
                    $CompanyPayment->package_id = $package->id;
                    $CompanyPayment->package_name = $package->name;
                    $CompanyPayment->package_amount = $package->amount;
                    $CompanyPayment->package_total_user = $package->total_user;
                    $CompanyPayment->json = $charge;
                    $CompanyPayment->save();
                }
                /*                 * *********End Company log************** */
                $emailTemplate = EmailTemplate::where('slug', 'company_registration')->first();
                if ($emailTemplate != null) {
                    $parse = [
                        'USERNAME' => $_POST['name'],
                        'EMAIL' => $_POST['email'],
                    ];
                    $emailTemplate->email_body = Helpers::parseTemplate($emailTemplate->email_body, $parse);
                    $emailTemplate->email_body = html_entity_decode($emailTemplate->email_body);
                    $emailTemplate->email = $_POST['email'];
                    $post = array('emailTemplate' => $emailTemplate, 'request' => $request, 'message' => $emailTemplate->email_body, 'email' => $emailTemplate->email);
                    Helpers::sendEmail($post);
                }
                return Redirect::back()->with('success', 'Registration has been successfully');
            } else {
                return Redirect::back()->with('err_msg', 'Something went erong please try again.');
            }
        } catch (Exception $e) {
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }

    public function forgotPasswordMail(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                        'email' => 'required|email|exists:users,email',
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
            $user = User::where('email', $request->email)->first();
            $emailTemplate = EmailTemplate::where('slug', 'forgot_password')->first();
            $link = Helpers::encode_url($request->email . "===" . date('d') . '===' . $user->id . '===' . $request->usertype);
          //  echo $request->email . "===" . date('d') . '===' . $user->id . '===' . $request->usertype;
           // dd($link);
            $emailLink = url('/forgotPasswordRequest/') . '/' . $link;
// dd($emailLink);
            if ($emailTemplate != null) {
                $parse = [
                    'USERNAME' => $user->name,
                    'FORGOTPASSWORD' => $emailLink,
                ];
                $emailTemplate->email_body = Helpers::parseTemplate($emailTemplate->email_body, $parse);
                $emailTemplate->email_body = html_entity_decode($emailTemplate->email_body);
                $emailTemplate->email = $request->email;
                $post = array('emailTemplate' => $emailTemplate, 'request' => $request, 'message' => $emailTemplate->email_body, 'email' => $emailTemplate->email);
                Helpers::sendEmail($post);
                User::where('id', $user->id)->update(['expiry_link' => 1]); //when mail sent that time set token 0 to 1  
                return Redirect::back()->with('success', 'Link to reset password has been sent successfully to your email address.');
            } else {
                return Redirect::back()->with('err_msg', 'Something went erong please try again.');
            }
        } catch (Exception $e) {
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }

    public function forgotPasswordRequest($data = null) {
        $email = explode("===", Helpers::decode_url($data));
        $days = date('d');
        if ($days != $email[1]) {
            return redirect('/users-login')->with('err_msg', 'Your link has been expire.');
        }
        //check expiry link  is 1 then link is active otherwise expiry
        $user = User::where('email', $email[0])->where('expiry_link', 1)->first();
        if (!$user) {
            return redirect('/users-login')->with('err_msg', 'Your link has been expire.');
        }
        if ($email[3] == 0) {
            return view('auth.resetPassword', compact('email'));
        } else {
            return view('front.forgotPassword', compact('email'));
        }
    }

    public function updateforgotPassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'password' => 'required',
                    'confirmPassword' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $user = User::where('email', $request->email)->where('id', $request->uid)->first();
        if ($user) {
            //link expiry when one time password reset
            $userPassword = User::where('id', $request->uid)->update(['password' => bcrypt($request->password), 'expiry_link' => 0]);
            if ($userPassword) {
                //when admin reset password that time usertype 0 and company reset password on front side that time usertype 1
                if ($request->usertype == 0) {
                    return redirect('/login')->with('success', "Your password has been change successfully.");
                } else {
                    return redirect('/users-login')->with('success', "Your password has been change successfully.");
                }
            }
        }
        return redirect('/users-login')->with('err_msg', 'Something went erong please try again.');
    }

    public function logout() {
        Auth::guard('front')->logout();
        return redirect('/users-login');
    }

}
