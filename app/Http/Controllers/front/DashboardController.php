<?php

namespace App\Http\Controllers\front;

use App\Http\Requests;
use App\User;
use App\EmailTemplate;
use App\Contactus;
use Helpers;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller {

    public function __construct(Request $request) {
        $auth = Auth::guard('front')->user();
        if (!$auth) {
            return redirect('/users-login')->with('err_msg', 'Login first');
        }
    }

    public function index() {
        
    }

    public function companyProfile() {
        dd(Auth::guard('front')->user());
    }

    protected function guard() {
        return Auth::guard('front');
    }

}
