<?php

namespace App\Http\Controllers;

use Helpers;
use Illuminate\Http\Request;
use App\Company;
use App\CompanyPayment;
use App\Point;
use App\Packages;
use App\User;
use App\CompanyPoint;
use DB;
use Validator;
use Redirect;
use Config;
use Carbon;
use Yajra\Datatables\Datatables;
use Auth;

class CompanyController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
//protected $table = 'room';
    public function __construct(Request $request) {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role_id != 1) {
//  return redirect('/index');
            }
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (Auth::user()->role_id != 1) {
            return redirect('/index');
        }
        return view('superadmin.company.index');
    }

    public function create() {
        if (Auth::user()->role_id != 1) {
            return redirect('/index');
        }
        $packageList = Packages::where('id', 1)->get();
        return view('superadmin.company.create', compact('packageList'));
    }

    public function store(Request $request) {
        try {
            if (Auth::user()->role_id != 1) {
                return redirect('/index');
            }
            $validator = Validator::make($request->all(), [
                        'company_name' => 'required|max:255|unique:companies,company_name',
                        'file_upload' => 'required',
                        'package_id' => 'required'
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
            if ($request->input('allow_anonymous')) {
                $allow_anonymous = 1;
            } else {
                $allow_anonymous = 0;
            }
            if ($request->input('allow_add_admin')) {
                $allow_add_admin = 1;
            } else {
                $allow_add_admin = 0;
            }
            
            DB::beginTransaction();
            $package = Packages::findOrFail($request->package_id);
           
           // dd($package);
            $company = new Company;
            $company->company_name = $request->input('company_name');
            $company->slug_name = $this->slugify($request->input('company_name'));
            $company->language = $request->input('language');
            $company->description = $request->input('company_description');
            $company->allow_anonymous = $allow_anonymous;
            $company->allow_add_admin = $allow_add_admin;
            $company->company_admin = 1; //$request->input('company_admin');
            $company->package_id = $package->id;
            $company->package_name = $package->name;
            $company->package_amount = $package->amount;
            $company->package_total_user = $package->total_user;
            $company->created_at = Carbon\Carbon::now();
            $file = $request->file('file_upload');
            if ($file != "") {
                $postData = array();
//echo "here";die();
                $fileName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $folderName = '/uploads/company_logo/';
                $destinationPath = public_path() . $folderName;
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
//$attachment = new Attachment;
                $company->company_logo = $safeName;
            }
            $company->save();
            $points = Point::select('activity', 'points', 'notes', DB::raw($company->id . ' as company_id'))->whereNull('deleted_at')->get()->toArray();
            $company_points = CompanyPoint::insert($points);
            DB::commit();
            if ($company_points) {
                return redirect()->route('company.index')->with('success', __('label.adCompany') .' '. __('label.ADDED_MESSAGE'));
            } else {
                return redirect()->route('company.index')->with('err_msg', '' .__('label.TRY_MESSAGE'));
            }
        } catch (\exception $e) {
            DB::rollback();
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }

    public function edit($id) {
        if (Auth::user()->role_id != 1) {
            return redirect('/index');
        }
        $id = Helpers::decode_url($id);
        $obj = new Company;
        $packageList = Packages::get();
        $company = $obj->where('id', $id)->orderBy('id', 'desc')->first();
        return view('superadmin.company.edit', compact('company', 'packageList'));
    }

    public function update(Request $request, $id) {
        try {
            if (Auth::user()->role_id != 1) {
                return redirect('/index');
            }
            $this->validate($request, [
                'company_name' => 'required|max:255|unique:companies,company_name,' . $id
            ]);
            $company = new Company;
            if ($request->input('allow_anonymous')) {
                $allow_anonymous = 1;
            } else {
                $allow_anonymous = 0;
            }
            if ($request->input('allow_add_admin')) {
                $allow_add_admin = 1;
            } else {
                $allow_add_admin = 0;
            }
//dd($request->all());
            $package = Packages::findOrFail($request->input('package_id'));
            $postData = array(
                'company_name' => $request->input('company_name'),
                'description' => $request->input('company_description'),
                'allow_anonymous' => $allow_anonymous,
                'allow_add_admin' => $allow_add_admin,
                'company_admin' => 1,
                'language' => $request->input('language'),
                'package_name' => $package->name,
                'package_amount' => $package->amount,
                'package_total_user' => $package->total_user,
                'updated_at' => Carbon\Carbon::now()
            );

            $file = $request->file('file_upload');
            if ($file != "") {
                $postData = array();
//echo "here";die();
                $fileName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $folderName = '/uploads/company_logo/';
                $destinationPath = public_path() . $folderName;
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
//$attachment = new Attachment;
                $postData['company_logo'] = $safeName;
            }
            $res = $company->where('id', $id)->update($postData);
            if ($res) {
                if ($file != "") {
                    if (file_exists(UPLOAD_PATH . $request->company_logo)) {
                        unlink(UPLOAD_PATH . $request->company_logo);
                    }
                }
                return redirect()->route('company.index')->with('success', __('label.adCompany') .' '. __('label.UPDATE_MESSAGE'));
            } else {
                return redirect()->route('company.index')->with('err_msg', '' .  __('label.TRY_MESSAGE'));
            }
        } catch (\exception $e) {
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }

    public function get_company(Request $request) {
       if (Auth::user()->role_id != 1) {
            return redirect('/index');
        }
        $company = new Company;
        $res = $company->select('*', DB::raw('CASE WHEN allow_anonymous = "1" THEN "Yes" ELSE "No" END AS anonymous,CASE WHEN allow_add_admin = "1" THEN "Yes" ELSE "No" END AS add_admin'))
                        ->whereNULL('deleted_at')->orderBy('id', 'desc');
        return Datatables::of($res)->filter(function ($query) use ($request) {
                    if ($request->has('company_name')) {
                        $query->where('company_name', 'like', "%{$request->get('company_name')}%");
                    }
                })->addColumn('actions', function ( $row ) {
                    return '<a href="' . route('company.edit', [Helpers::encode_url($row->id)]) . '" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                })->rawColumns(['actions'])->make(true);
    }

    /*
     * use the following  function to set company profile detail.
     */

    public function companyEdit() {
        try {
            //\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            //dd(\Stripe\Subscription::retrieve('sub_CIUNxbpBP0DngV'));
            if (Auth::user()->role_id == 1) {
                return redirect('/index');
            }
            $id = Auth::user()->company_id;
            $userid = Auth::user()->id;
            $packageList = Packages::get();
            $company = Company::where('id', $id)->first();
            $userList = User::where('id', $userid)->first();

            return view('companyadmin.company.edit', compact('company', 'packageList', 'userList'));
        } catch (Exception $e) {
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }

    /*
     * Use the following function to update company  profile by compnay admin
     */

    public function updateCompany(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                        'language' => 'required'
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
            $id = Auth::user()->company_id;
            $company = Company::findOrFail($id);
            $company->description = $request->get('company_description', null);
            $company->language = $request->get('language', null);
            $file = $request->file('file_upload');
            if ($file != "") {
                $fileName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $folderName = '/uploads/company_logo/';
                $destinationPath = public_path() . $folderName;
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                $company->company_logo = $safeName;
            }
            if ($company->save()) {
                return Redirect::back()->with('success', __('label.adCompany') .' '. __('label.UPDATE_MESSAGE'));
            } else {
                return Redirect::back()->with('err_msg', '' .  __('label.TRY_MESSAGE'));
            }
        } catch (Exception $e) {
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }

    public function packageUpgrade(Request $request) {
        try {
            //dd($request->all());
            $id = Auth::user()->company_id;
            $company = Company::findOrFail($id);
            $p_id = $company->package_id;
            $new_p_id = $request->new_package_id; //this id for package table (PK)

            $package = Packages::findOrFail($new_p_id);
            $sourceId = $request->stripeToken;
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            if ($new_p_id == 1) {
                $updatePlan = \Stripe\Subscription::retrieve($company->subscription_id);
                $updatePlan->cancel();                
            } else {

                if ($company->stripe_customer_id == null || $company->stripe_customer_id == '') {

                    //when user  in free plan after then upgrad the plan
                    $sourceId = $request->stripeToken;
                    /* End Create source */

                    /* create customer */
                    try {
                        $customer = \Stripe\Customer::create([
                                    'source' => $sourceId,
                                    'email' => Auth::user()->email,
                                    'metadata' => [
                                        "First Name" => Auth::user()->name,
                                        "Last Name" => Auth::user()->name
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
                    /* End create customer */
                    $customerID = $customer->id;
                    $updatePlan = $this->createSubscription($customerID, $package->product_id);
                    
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
                    //upgrad plan in stripe
                    if ($company->stripe_customer_id == '' || $company->stripe_customer_id == null) {
                        return redirect('companyEdit')->with('err_msg', '' . __('label.TRY_MESSAGE'));
                    }

                    //  $companyPaymentList = CompanyPayment::where('stripe_cust_id',$company->stripe_customer_id);
                    if ($company->package_id == 1) {
                        $updatePlan = $this->createSubscription($company->stripe_customer_id, $package->product_id);
                    } else {

                        $subscription = \Stripe\Subscription::retrieve($company->subscription_id);
                        $getSubscriptionId = $subscription->items->data[0]->id;
                        /* create subscription */
                        try {
                            if ($subscription->status == 'canceled') {
                                $updatePlan = $this->createSubscription($company->stripe_customer_id, $package->product_id);
                            } else {
                                $updatePlan = \Stripe\Subscription::update($company->subscription_id, [
                                            'items' => [
                                                [
                                                    'id' => $getSubscriptionId,
                                                    'plan' => $package->product_id,
                                                ],
                                            ],
                                            'prorate' => false,
                                ]);
                            }
                            //return $updatePlan;
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
                    }
                    /* payment charge */
                    try {
                        $charge = \Stripe\Charge::create([
                                    'amount' => $package->amount * 100,
                                    'currency' => 'usd',
                                    //'customer' => $updatePlan->customer,
                                    'customer' => $company->stripe_customer_id,
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
                }
            }
            
            $user = User::find(Auth::user()->id);
            $user->stripe_customer_id = $updatePlan->customer;
            $user->save();
            
            $company->package_id = $package->id;
            $company->package_name = $package->name;
            $company->package_amount = $package->amount;
            $company->package_total_user = $package->total_user;
            $company->subscription_id = $updatePlan->id;
            $company->stripe_customer_id = $updatePlan->customer;
        //    return $charge;
            if ($company->save()) {
                if ($new_p_id != 1) {
                    $CompanyPayment = new CompanyPayment;
                    $CompanyPayment->amount = $package->amount;
                    $CompanyPayment->currency = $charge->currency;
                    $CompanyPayment->transaction_id = $charge->balance_transaction;
                    $CompanyPayment->payment_status = $charge->paid;
                    //$CompanyPayment->card_brand = $charge->source->card->brand;
                    //$CompanyPayment->card_country = $charge->source->card->country;
                    //$CompanyPayment->card_exp_month = $charge->source->card->exp_month;
                    //$CompanyPayment->card_exp_year = $charge->source->card->exp_year;
                     $CompanyPayment->card_brand = $charge->source->brand;
                    $CompanyPayment->card_country = $charge->source->country;
                    $CompanyPayment->card_exp_month = $charge->source->exp_month;
                    $CompanyPayment->card_exp_year = $charge->source->exp_year;
                    $CompanyPayment->status = $charge->status;
                    $CompanyPayment->company_id = $company->id;
                    $CompanyPayment->stripe_cust_id = $company->stripe_customer_id;
                    $CompanyPayment->billing_start_date = date('Y-m-d');
                    $CompanyPayment->billing_end_date = date('Y-m-d', strtotime("+30 days"));
                    $CompanyPayment->package_id = $package->id;
                    $CompanyPayment->package_name = $package->name;
                    $CompanyPayment->package_amount = $package->amount;
                    $CompanyPayment->package_total_user = $package->total_user;
                    $CompanyPayment->json = $charge;
                    $CompanyPayment->subscription = $updatePlan;
                    $CompanyPayment->save();
                }
            }
            return redirect('companyEdit')->with('success', __('label.Your plan updated successfully.'));
            /* end payment chage */
        } catch (Exception $e) {
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }

    public function createSubscription($customerID = null, $product_id) {
        try {
            $subscription = \Stripe\Subscription::create(array(
                        "customer" => $customerID,
                        "items" => array(
                            array(
                                "plan" => $product_id, //this is get from database and set from stripe payment login
                            ),
                        )
            ));
            return $subscription;
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
    }
    
     static public function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
    
}

?>
