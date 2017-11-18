<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\User;
use App\SecurityQuestion;
use App\UserSecurityQuestion;
use DB;
use Validator;
use Redirect;
use Config;
use Carbon;
use Yajra\Datatables\Datatables;
use Auth;


class DashboardController extends Controller {
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //protected $table = 'room';
    public $folder;
    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            if(Auth::user()->role_id == 1) {
                $this->folder = 'superadmin';
            }else if(Auth::user()->role_id == 2) {
                $this->folder = 'companyadmin';
            }else if(Auth::user()->role_id == 3) {
                $this->folder = 'employee';
            }
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd("here");
        return view($this->folder.'.dashboard');
    }
    public function edit_profile(Request $request) {
        if(Auth::user()) { 
            $user_id = Auth::user()->id;
            $user = User::where('id', $user_id)->first();
            $questions = SecurityQuestion::all();
            $userquestions = UserSecurityQuestion::where('user_id', $user_id)->get();
            return view($this->folder.'.users.edit_profile', compact('user', 'questions', 'userquestions'));
        } else {
            return redirect('/index');
        }
    }

    public function update_profile(Request $request) {
        try {
            $postData = "";
            $user_id = Auth::user()->id;
            $validator = Validator::make($request->all(), [
                        'name' => 'required',
                        'email' => 'required|email|unique:users,email,' . $user_id,
                        'profile_image' => 'image|mimes:jpg,png,jpeg',
            ]);
            if ($validator->fails()) {
                $request->session()->flash('failure', $validator->errors());
                return Redirect::back()->withErrors($validator);
            }
            $postData = array('name' => $request->name, 'email' => $request->email);
            if (!empty($_POST['google_link'])) {
                $postData['google_id'] = $request->google_link;
            }
            if (!empty($_POST['linkedin_link'])) {
                $postData['linkedin_id'] = $request->linkedin_link;
            }
            $file = $request->file('profile_image');
            if ($file != "") {
                //echo "here";die();
                $fileName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension() ?: 'png';
                $folderName = '/uploads/profile_pic/';
                $destinationPath = public_path() . $folderName;
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                $postData['profile_image'] = $safeName;
            }
            if (!empty($postData)) {
                if (User::Where('id', $user_id)->update($postData)) {
                    return Redirect::back()->with('success', 'Profile ' . Config::get('constant.UPDATE_MESSAGE'));
                } else {
                    return Redirect::back()->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
                }
            }
        }catch (\exception $e) {
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }
    
}
?>
