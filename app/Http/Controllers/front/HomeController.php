<?php

namespace App\Http\Controllers\front;

use App\Http\Requests;
use App\Packages;
use App\Faqs;
use App\EmailTemplate;
use App\Contactus;
use Helpers;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function __construct(Request $request) {
        
    }

    public function index() {
        $packageList = Packages::get();
        return view('front.home', compact('packageList'));
    }

    public function how_it_works() {
        return view('front.howitwork');
    }

    public function why_us() {
        return view('front.whyus');
    }

    public function packages() {
        $packageList = Packages::get();
        return view('front.package', compact('packageList'));
    }

    public function faqs() {
        $faqsList = Faqs::get();
        return view('front.faqs', compact('faqsList'));
    }

    /*
     * Use the following function to send email using contact us and faqs page form
     */

    public function faqsEmail(Request $request) {
        if (isset($_POST['name']) && $_POST['name'] == '') {
            return json_encode(array('status' => 0, 'msg' => 'Name is require.', 'name' => $_POST['name']));
        }
        if (isset($_POST['email']) && $_POST['email'] == '') {
            return json_encode(array('status' => 0, 'msg' => 'Email is require.', 'email' => $_POST['email']));
        }
        $contactus = new Contactus;
        $contactus->name = $_POST['name'];
        if (isset($_POST['mobile']) && $_POST['mobile'] != '') {
            $contactus->mobile = $_POST['mobile'];
        }
        $contactus->email = $_POST['email'];
        $contactus->message = $_POST['message'];
        //return ($contactus);
        if ($contactus->save()) {
            if (isset($_POST['mobile']) && $_POST['mobile'] != '') {
                $emailTemplate = EmailTemplate::where('slug', 'contactUs')->first();
            } else {
                $emailTemplate = EmailTemplate::where('slug', 'contactUs_Faqs')->first();
            }

            if ($emailTemplate != null) {
                if (isset($_POST['mobile'])) {
                    //it is use for contact us
                    $parse = [
                        'FULLNAME' => $_POST['name'],
                        'EMAIL' => $_POST['email'],
                        'MOBILE' => $_POST['mobile'],
                        'MESSAGE' => $_POST['message'],
                    ];
                } else {
                    //it is use for faqs page data
                    $parse = [
                        'FULLNAME' => $_POST['name'],
                        'EMAIL' => $_POST['email'],
                        'MESSAGE' => $_POST['message'],
                    ];
                }
                $emailTemplate->email_body = Helpers::parseTemplate($emailTemplate->email_body, $parse);
                $emailTemplate->email_body = html_entity_decode($emailTemplate->email_body);
                $emailTemplate->email = $_POST['email'];
                $post = array('emailTemplate' => $emailTemplate, 'request' => $request, 'message' => $emailTemplate->email_body, 'email' => $emailTemplate->email);
                //dd($post);
                Helpers::sendEmail($post);
            }
            return json_encode(array('status' => 1, 'msg' => "Your request submitted successfully."));
        } else {

            return json_encode(array('status' => 0, 'msg' => "Something went wrong."));
        }
    }

    public function contactUs() {
        return view('front.contactUs');
    }

    public function users_login() {
        $auth = Auth::guard('front')->user();

        if ($auth != null) {
            return redirect('/companyProfile');
        }
        $packageList = Packages::get();
        return view('front.loginReg', compact('packageList'));
    }

    public function registerPackage($package=null) {
          $auth = Auth::guard('front')->user();

        if ($auth != null) {
            return redirect('/companyProfile');
        }
        $packageList = Packages::get();
        return view('front.loginReg', compact('packageList','package'));
    }

    public function privacypolicy() {
        return view('front.privacypolicy');
    }

    public function teams_condition() {
        return view('front.teamscondition');
    }

}
