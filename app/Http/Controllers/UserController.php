<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Company;
use App\SecurityQuestion;
use App\UserSecurityQuestion;
use DB;
use Validator;
use Redirect;
use Config;
use Carbon;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $roles = Role::whereIn('id', [2, 3])->get();
        return view('users.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $roles = Role::whereIn('id', [2, 3])->get();
        $companies = Company::whereNull('deleted_at')->get();
        return view('users.create', compact('roles', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $this->validate($request, [
                'user_name' => 'required',
                'user_email' => 'required|email|unique:users,email',
                'company_id' => 'required',
                'role_id' => 'required'
            ]);
            if ($request->input('is_active')) {
                $is_active = 1;
            } else {
                $is_active = 0;
            }
            if ($request->input('is_suspended')) {
                $is_suspended = 1;
            } else {
                $is_suspended = 0;
            }

            $user = new User;
            $user->name = $request->input('user_name');
            $user->email = $request->input('user_email');
            $user->role_id = $request->input('role_id');
            $user->company_id = $request->input('company_id');
            $user->is_active = $is_active;
            $user->is_suspended = $is_suspended;
            $user->password = Hash::make('123456');
            $user->created_at = Carbon\Carbon::now();
            if ($user->save()) {
                return redirect()->route('user.index')->with('success', 'User ' . Config::get('constant.ADDED_MESSAGE'));
            } else {
                return redirect()->route('user.index')->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
            }
        }catch (\exception $e) {
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id, User $user) {

        $users = User::all();

        return Datatables::of($users)->make();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user = User::where('id', $id)->first();
        $roles = Role::whereIn('id', [2, 3])->get();
        $companies = Company::whereNull('deleted_at')->get();
        return view('users.edit', compact('user', 'roles', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        try {
            $this->validate($request, [
                'user_name' => 'required',
                'user_email' => 'required|email|unique:users,email,' . $id,
                'company_id' => 'required',
                'role_id' => 'required'
            ]);
            if ($request->input('is_active')) {
                $is_active = 1;
            } else {
                $is_active = 0;
            }
            if ($request->input('is_suspended')) {
                $is_suspended = 1;
            } else {
                $is_suspended = 0;
            }

            $postData = array('name' => $request->input('user_name'), 'email' => $request->input('user_email'), 'role_id' => $request->input('role_id'), 'company_id' => $request->input('company_id'), 'is_active' => $is_active, 'is_suspended' => $is_suspended, 'updated_at' => Carbon\Carbon::now());
            $res = User::where('id', $id)->update($postData);
            if ($res) {
                return redirect()->route('user.index')->with('success', 'User ' . Config::get('constant.UPDATE_MESSAGE'));
            } else {
                return redirect()->route('user.index')->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
            }
        }catch (\exception $e) {
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        //
    }

    public function userListing(Request $request) {
        //$users = User::whereNull('deleted_at')->where('id', '!=' , Auth::user()->id)->get();
        if(Auth::user()) { 
            $auth = Auth::user();
            $roleId = $auth->role_id;
            $users = new User;
            $query = $users->select('*', DB::raw('CASE WHEN is_active = "1" THEN "Yes" ELSE "No" END AS active,CASE WHEN is_suspended = "1" THEN "Yes" ELSE "No" END AS suspended,CASE WHEN role_id = "2" THEN "Admin" WHEN role_id = "3" THEN "Employee" END AS role'));
            if ($roleId == 1) {
                $query = $query->whereIn('role_id', [2, 3]);
            } else if ($roleId == 2) {
                $query = $query->whereIn('role_id', [3])->where('company_id', '=', $auth->company_id);
            }
            $res = $query->where('id', '!=', Auth::user()->id)->whereNULL('deleted_at');

            return Datatables::of($res)->filter(function ($query) use ($request) {
            if ($request->has('user_name')) {
                $query->where('name', 'like', "%{$request->get('user_name')}%");
            }
            if ($request->has('user_email')) {
                $query->where('email', 'like', "%{$request->get('user_email')}%");
            }
            if ($request->has('role_id')) {
                $query->where('role_id', 'like', "%{$request->get('role_id')}%");
            }
            })->addColumn('actions', function ( $row ) {
                return '<a href="' . route('user.edit', [$row->id]) . '" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
            })->rawColumns(['actions'])->make(true);
        } else {
            return redirect('/index');
        }
    }

    public function edit_profile(Request $request) {
        if(Auth::user()) { 
            $user_id = Auth::user()->id;
            $user = User::where('id', $user_id)->first();
            $questions = SecurityQuestion::all();
            $userquestions = UserSecurityQuestion::where('user_id', $user_id)->get();
            return view('users.edit_profile', compact('user', 'questions', 'userquestions'));
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
                        'profile_image' => 'image|mimes:jpg,png',
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
    public function security_update_profile(Request $request) {
        try {
            if(Auth::user()) { 
                $user_id = Auth::user()->id;
                $validator = Validator::make($request->all(), [
                    'question_1' => 'required',
                    'answer_1' => 'required',
                    'question_2' => 'required',
                    'answer_2' => 'required',
                    'question_3' => 'required',
                    'answer_3' => 'required'
                ]);
                if ($validator->fails()) {
                    $request->session()->flash('failure', $validator->errors());
                    return Redirect::back()->withErrors($validator);
                }
                $security = UserSecurityQuestion::where('user_id', $user_id)->get();
                if (!empty($security)) {
                    $deleteSecurity = UserSecurityQuestion::where('user_id', $user_id)->delete();
                }
                $now = Carbon\Carbon::now();
                $data = [
                    ['question_id' => $request->question_1, 'answer' => "$request->answer_1", 'user_id' => $user_id, 'created_at' => $now, 'updated_at' => $now],
                    ['question_id' => $request->question_2, 'answer' => "$request->answer_2", 'user_id' => $user_id, 'created_at' => $now, 'updated_at' => $now],
                    ['question_id' => $request->question_3, 'answer' => "$request->answer_3", 'user_id' => $user_id, 'created_at' => $now, 'updated_at' => $now]
                ];
                $result = UserSecurityQuestion::insert($data);
                if ($result) {
                    return Redirect::back()->with('success', 'Profile ' . Config::get('constant.UPDATE_MESSAGE'));
                } else {
                    return Redirect::back()->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
                }
            } else {
                return redirect('/index');
            }
        }catch (\exception $e) {
            //$e->getMessage();
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }
    public function changepassword_update_profile(Request $request) {
        try {
            if(Auth::user()) { 
                $user_id = Auth::user()->id;
                $validator = Validator::make($request->all(), [
                    'old_password' => 'required|min:6',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|min:6|same:new_password',
                ]);
                if ($validator->fails()) {
                    $request->session()->flash('failure', $validator->errors());
                    return Redirect::back()->withErrors($validator);
                }
                $user = User::where('id', Auth::user()->id)->first();
                $old_password = $request->old_password;
                $new_password = $request->new_password;
                if(Hash::check($old_password, $user->password)){
                    //Your update here
                    $data['password'] = Hash::make($new_password);
                    if($user->update($data)) {
                        return Redirect::back()->with('success', 'Password ' . Config::get('constant.UPDATE_MESSAGE'));
                    }else {
                       return Redirect::back()->with('err_msg', '' . Config::get('constant.TRY_MESSAGE')); 
                    }
                    
                }
                else {
                    return Redirect::back()->with('err_msg', Config::get('constant.OLD_PASSWORD_NOT_MATCH'));
                }
            }else {
                return redirect('/index');
            }
        }
          catch (\exception $e) {
            //$e->getMessage();
            return Redirect::back()->with('err_msg', $e->getMessage());
        }      
    }
    public function notification_update_profile(Request $request) {
        
    }
}
