<?php

namespace App\Http\Controllers;

use Helpers;
use Illuminate\Http\Request;
use App\Settings;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Redirect;
use Config;
use Carbon;
use Yajra\Datatables\Datatables;

class SettingsController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //protected $table = 'room';
    public function __construct(Request $request) {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role_id != 1) {
                return redirect('/index');
            }
            return $next($request);
        });
    }

    /**
     * Use the following function to get block list design
     */
    public function index() {
        $id = 1;
        $settings = Settings::findOrFail($id);
        return view('superadmin.settings.edit', compact('settings'));
    }

    public function update(Request $request, $id) {
        try {            
            
            $settings = Settings::findOrFail($id);
           
            $settings->email1 = $request->get('email1', null);
            $settings->email2 = $request->get('email2', null);
            $settings->from_email = $request->get('from_email', null);
            $settings->support_email = $request->get('support_email', null);
            $settings->copyright = $request->get('copyright', null);
            $settings->facebook = $request->get('facebook', null);
            $settings->twitter = $request->get('twitter', null);
            $settings->instagram = $request->get('instagram', null);
            $settings->phone = $request->get('phone', null);
            $settings->mobile = $request->get('mobile', null);            
            if ($settings->save()) {
                return Redirect::route('settings.index')->with('success', 'Settings ' . Config::get('constant.UPDATE_MESSAGE'));
            } else {
                return Redirect::route('settings.index')->with('error', '' . Config::get('constant.TRY_MESSAGE'));
            }
        } catch (\exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

}

?>
