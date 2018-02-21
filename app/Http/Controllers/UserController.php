<?php

namespace App\Http\Controllers;

use App\Company;
use App\FollowUser;
use App\Group;
use App\GroupUser;
use App\Role;
use App\User;
use App\UserSecurityQuestion;
use Auth;
use Carbon;
use Config;
use DB;
use Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Redirect;
use Validator;
use View;
use Yajra\Datatables\Datatables;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public $folder;

	public function __construct(Request $request) {
		$this->middleware(function ($request, $next) {
			//      if (Auth::user()->role_id == 3) {
			//          return redirect('/index');
			//      }
			if (Auth::user()->role_id == 1) {
				$this->folder = 'superadmin';
			} else if (Auth::user()->role_id == 2) {
				$this->folder = 'companyadmin';
			} else if (Auth::user()->role_id == 3) {
				$this->folder = 'employee';
			}

			return $next($request);
		});
	}

	public function index() {
		if (Auth::user()) {
			$company_id = Auth::user()->company_id;
			$usercompany_query = Company::whereNull('deleted_at');
			if (Auth::user()->role_id > 1) {
				$usercompany_query = $usercompany_query->where('id', $company_id);
			}

			$usercompany = $usercompany_query->first();

			if ($usercompany) {
				if ($usercompany->allow_add_admin == 1) {
					$role_id = [2, 3];
				} else {
					$role_id = [3];
				}
				$roles = Role::whereIn('id', $role_id)->get();
				$companies = Company::whereNull('deleted_at')->get();
				$user_query = User::with(['following', 'followers'=>function($q) {
                                    $q->where(['sender_user_id'=>Auth::user()->id,'status'=>1]);
                                }])->withCount('followers')->where('role_id', 3);
				if (Auth::user()->role_id == 2) {
					$user_query = $user_query->where('company_id', $company_id);
				}
				$users_count = count($user_query->get());
				$users = $user_query->orderByDesc('created_at')->limit(POST_DISPLAY_LIMIT)->get();
				$company_admins = User::with(['following', 'followers'])->where('role_id', 2)->get();
				//return $users;
				return view($this->folder . '.users.index', compact('roles', 'companies', 'users', 'users_count', 'company_admins','follow'));
			} else {
				$role_id = [3];
				$roles = Role::whereIn('id', $role_id)->get();
				$companies = Company::whereNull('deleted_at')->get();
				$user_query = User::with(['following', 'followers'=>function($q) {
                                    $q->where(['sender_user_id'=>Auth::user()->id,'status'=>1]);
                                }])->withCount('followers')->where('role_id', 3);
				if (Auth::user()->role_id == 2) {
					$user_query = $user_query->where('company_id', $company_id);
				}
				$users_count = count($user_query->get());
				$users = $user_query->orderByDesc('created_at')->limit(POST_DISPLAY_LIMIT)->get();
				$company_admins = User::with(['following', 'followers'])->where('role_id', 2)->get();
				return view($this->folder . '.users.index', compact('roles', 'companies', 'users', 'users_count', 'company_admins'));
			}
		} else {
			return redirect('/index');
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
                //if (Auth::user()) {
		if (Auth::user()->role_id != 3) {
			$currUser = Auth::user();
			$company_id = Auth::user()->company_id;
			$usercompany = Company::whereNull('deleted_at')->where('id', $company_id)->first();
			if ($usercompany) {
				if ($usercompany->allow_add_admin == 1) {
					$role_id = [2, 3];
				} else {
					$role_id = [3];
				}
				$roles = Role::whereIn('id', $role_id)->get();
				$companies = [];
				if ($currUser->role_id == '2') {
					//if company admin is adding a user then show him the groups of his company only
					$companies = Company::where('id', $company_id)->get();
				} else {
					$companies = Company::whereNull('deleted_at')->get();
				}

				return view($this->folder . '.users.create', compact('roles', 'companies'));
			} else {
				$companies = Company::all();
				$role_id = [2, 3];
				$roles = Role::whereIn('id', $role_id)->get();
				return view($this->folder . '.users.create', compact('roles', 'companies'));
			}
               // }
		} else {
			return redirect('/index')->with('error_msg', "You don't have rights to create a user.");
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		try {
			$now = Carbon\Carbon::now();
			/*$this->validate($request, [
				'user_name' => 'required',
				'user_email' => 'required|email|unique:users,email',
				'company_id' => 'required',
				'role_id' => 'required',
			]);*/
			$validator = Validator::make($request->all(),
				[
					'user_name' => 'required',
					'user_email' => 'required|email|unique:users,email',
					'company_id' => 'required',
					'role_id' => 'required',
				]);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			}
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
			$password = str_random(6);
			//$password = "123456";
			$email = $request->input('user_email');

			$user = new User;
			$user->name = $request->input('user_name');
			$user->email = $request->input('user_email');
			$user->role_id = $request->input('role_id');
			$user->company_id = $request->input('company_id');
			$user->is_active = $is_active;
			$user->is_suspended = $is_suspended;
			$user->password = Hash::make($password);
			$user->created_at = Carbon\Carbon::now();
			$this->custom_send_mail($email, $password, $request->input('user_name'));
			if ($user->save()) {
				if (!empty($request->user_groups)) {
// add user in the groups selected
					$userId = $user->id;
					$grp = [];
					foreach ($request->user_groups as $data) {
						$grp[] = ['user_id' => $userId, 'group_id' => $data, 'is_admin' => 0, 'created_at' => $now, 'updated_at' => $now];
					}
//                    dd($grp);
					GroupUser::insert($grp);
				}

				return redirect()->route('user.index')->with('success', 'User ' . Config::get('constant.ADDED_MESSAGE'));
			} else {
				return redirect()->route('user.index')->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
			}
		} catch (\exception $e) {
			return Redirect::back()->with('err_msg', $e->getMessage());
		}
	}

	public function custom_send_mail($email, $password, $user_name) {

		$emailTemplate = Helpers::getEmailTemplateBySlug('NEW_USER');
//        echo $emailTemplate->email_body;die;
		if ($emailTemplate != null) {
			$parse = [
				'NAME' => $user_name,
				'EMAIL' => $email,
				'PASSWORD' => $password,
				'LINK' => route('login'),
			];
			$subject = $emailTemplate->subject;
			$parsedTemplate = Helpers::parse_template($emailTemplate->email_body, $parse, $emailTemplate->allowed_fields);
			$message = $parsedTemplate['data'];

			$message = html_entity_decode($message);
			$adminEmail = env('MAIL_FROM_ADDRESS', 'testacc2016@gmail.com');
			$mailData = array(
				'to' => $email,
				'from' => $adminEmail,
				'from_name' => env('MAIL_FROM_NAME', 'Dico'),
				'reply_to' => $adminEmail, // reply_to
				'subject' => $subject,
				'message' => $message,
			);

			$res = Helpers::sendMail($mailData);

			return $res;
		}

		return "template not found";
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
		if (Auth::user()) {
			$auth = Auth::user();
			$roleId = $auth->role_id;
			//$users = new User;
			$query = User::with(['company', 'followers', 'following'])->select('*', DB::raw('CASE WHEN is_active = "1" THEN "Yes" ELSE "No" END AS active,CASE WHEN is_suspended = "1" THEN "Yes" ELSE "No" END AS suspended,CASE WHEN role_id = "2" THEN "Admin" WHEN role_id = "3" THEN "Employee" END AS role'));
			if ($roleId == 1) {
				$query = $query->whereIn('role_id', [2, 3]);
			} else if ($roleId == 2) {
				$query = $query->where('role_id', '!=', 1)->where('company_id', '=', $auth->company_id);
			}
			$res = $query->where('id', '!=', Auth::user()->id)->whereNULL('deleted_at')->orderBy('id', 'desc');
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
				if ($request->has('company_id')) {
					$query->where('company_id', 'like', "%{$request->get('company_id')}%");
				}
			})->addColumn('actions', function ($row) {
				return '<a href="' . route('user.edit', [Helpers::encode_url($row->id)]) . '" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
			})->rawColumns(['actions'])->make(true);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\User                $user
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$id = Helpers::decode_url($id);
		if (Auth::user()) {
			$user = User::find($id);
                        //dd($user);
			$company_id = $user->company_id;
			$usercompany = Company::whereNull('deleted_at')->where('id', $company_id)->first();
                        //dd($usercompany);
			if ($usercompany) {
				if ($usercompany->allow_add_admin == 1) {
					$role_id = [2, 3];
				} else {
					$role_id = [3];
				}
				//DB::connection()->enableQueryLog();
				$user = User::with('followers')->with(['following' => function ($q) {
					$q->where('sender_user_id', Auth::user()->id)->first(); // '=' is optional
				}])->where('id', $id)->first();
				//dd(DB::getQueryLog());
				$roles = Role::whereIn('id', $role_id)->get();
				$companies = Company::whereNull('deleted_at')->get();

				$user_group_ids = GroupUser::where('user_id', $id)->get()->pluck('group_id')->toArray();
				$groups = Group::whereIn('id', $user_group_ids)->where('company_id', $company_id)->get();
				return view($this->folder . '.users.edit', compact('user', 'roles', 'companies', 'groups', 'user_group_ids'));
			}
		} else {
			return redirect('/index');
		}
	}

	public function update(Request $request, $id) {
            //dd($request->all());
		try {
			$validator = Validator::make($request->all(),
                        [
                                'user_name' => 'required',
                                'user_email' => 'required|email|unique:users,email,' . $id,
                                'role_id' => 'required',
                        ]);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			}
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
			$user = User::find($id);
			$company_id = $request->input('company_id');

			$user_group_ids = GroupUser::where('user_id', $id)->get()->pluck('group_id')->toArray();

			$removed = array_diff($user_group_ids, $request->input('user_groups'));
			if (!empty($removed) && count($removed) > 0) {
				foreach ($removed as $val) {
					$group = Group::find($val);
					if (!empty($group)) {
						if ($group->group_owner == $user->id) {
							$group_name = $group->group_name;
							DB::rollBack();

							return back()->with('err_msg', 'User cannot be removed from ' . $group_name . ' as the user is the group owner.');
						} else {
							GroupUser::where(['user_id' => $user->id, 'group_id' => $group->id])->delete();
						}
					}
				}
			}

			$new_added = array_diff($request->input('user_groups'), $user_group_ids);
			if (!empty($new_added) && count($new_added) > 0) {
				$addData = [];
				$now = Carbon\Carbon::now();
				foreach ($new_added as $val) {
					$addData[] = ['user_id' => $user->id, 'group_id' => $group->id, 'company_id' => $company_id, 'created_at' => $now, 'updated_at' => $now];
				}
				GroupUser::insert($addData);
			}

			//dd([$request->input('user_groups'), $removed, $new_added]);
			$postData = array('name' => $request->input('user_name'), 'email' => $request->input('user_email'), 'role_id' => $request->input('role_id'), 'company_id' => $request->input('company_id'), 'is_active' => $is_active, 'is_suspended' => $is_suspended, 'updated_at' => Carbon\Carbon::now());
                        $res = User::where('id', $id)->update($postData);
			if ($res) {
				DB::commit();
				return redirect()->route('user.index')->with('success', 'User ' . Config::get('constant.UPDATE_MESSAGE'));
			} else {
				DB::rollBack();
				return redirect()->route('user.index')->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
			}
		} catch (\exception $e) {
//            dd($e);
			return Redirect::back()->with('err_msg', $e->getMessage());
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\User $user
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function security_update_profile(Request $request) {
		try {
			if (Auth::user()) {
				$user_id = Auth::user()->id;
				$validator = Validator::make($request->all(), [
					'question_1' => 'required',
					'answer_1' => 'required',
					'question_2' => 'required',
					'answer_2' => 'required',
					'question_3' => 'required',
					'answer_3' => 'required',
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
					['question_id' => $request->question_3, 'answer' => "$request->answer_3", 'user_id' => $user_id, 'created_at' => $now, 'updated_at' => $now],
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
		} catch (\exception $e) {
			//$e->getMessage();
			return Redirect::back()->with('err_msg', $e->getMessage());
		}
	}

	public function changepassword_update_profile(Request $request) {
		try {
			if (Auth::user()) {
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
				if (Hash::check($old_password, $user->password)) {
					//Your update here
					$data['password'] = Hash::make($new_password);
					if ($user->update($data)) {
						return Redirect::back()->with('success', 'Password ' . Config::get('constant.UPDATE_MESSAGE'));
					} else {
						return Redirect::back()->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
					}
				} else {
					return Redirect::back()->with('err_msg', Config::get('constant.OLD_PASSWORD_NOT_MATCH'));
				}
			} else {
				return redirect('/index');
			}
		} catch (\exception $e) {
			//$e->getMessage();
			return Redirect::back()->with('err_msg', $e->getMessage());
		}
	}

	public function notification_update_profile(Request $request) {

	}

	public function follow($id = null) {
		try {
			if (Auth::user()) {
				$user_id = Auth::user()->id;
				if (FollowUser::where(array('sender_user_id' => $user_id, 'receiver_user_id' => $id))->exists()) {
					$data = array("status" => 1, "updated_at" => Carbon\Carbon::now());
					$where = array("sender_user_id" => $user_id, "receiver_user_id" => $id);
					$follow = FollowUser::where($where)->update($data);
					if ($follow) {
						return redirect()->route('user.edit', $id)->with('success', 'Follow successfully.');
					} else {
						return redirect()->route('user.edit', $id)->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
					}
				} else {
					$follow = new FollowUser;
					$follow->sender_user_id = $user_id;
					$follow->receiver_user_id = $id;
					$follow->status = 1;
					$follow->created_at = Carbon\Carbon::now();
					if ($follow->save()) {
						return redirect()->route('user.edit', $id)->with('success', 'Follow successfully.');
					} else {
						return redirect()->route('user.edit', $id)->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
					}
				}
			} else {
				return redirect('/index');
			}
		} catch (\exception $e) {
			//$e->getMessage();
			return Redirect::back()->with('err_msg', $e->getMessage());
		}
	}

	public function unfollow($id = null) {
		try {
			if (Auth::user()) {
				$user_id = Auth::user()->id;
				$data = array("status" => 2, "updated_at" => Carbon\Carbon::now());
				$where = array("sender_user_id" => $user_id, "receiver_user_id" => $id);
				$follow = FollowUser::where($where)->update($data);
				if ($follow) {
					return redirect()->route('user.edit', $id)->with('success', 'Unfollow successfully.');
				} else {
					return redirect()->route('user.edit', $id)->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
				}
			} else {
				return redirect('/index');
			}
		} catch (\exception $e) {
			//$e->getMessage();
			return Redirect::back()->with('err_msg', $e->getMessage());
		}
	}

	/* return json response to show groups listing while adding new users */

	public function getCompanyGroups(Request $request) {
		$data = ['status' => 0, 'msg' => 'Please try again later.', 'data' => []];
		if ($request->ajax()) {
			$companyId = $request->get('companyId');
			$groups = Group::where('company_id', $companyId)->orderByDesc('id')->get();
			$data = ['status' => 1, 'msg' => 'Group listing successfull.', 'data' => $groups];
		}

		return response()->json($data, '200');
	}

	public function getUserProfile(Request $request) {
		if ($request->ajax()) {
			$user_id = $request->input('user_id');
			$response_data = ['status' => 0, 'data' => []];
			$profile = User::where('id', $user_id)->first();
			if (!empty($profile)) {
				$response_data = ['status' => 1, 'data' => $profile];
			}

			return response()->json($response_data);
		}
	}

	/*Users listing for super users */
	public function getEmployeeListing(Request $request) {

		//$users = User::whereNull('deleted_at')->where('id', '!=' , Auth::user()->id)->get();
		if (Auth::check()) {
			$currUser = Auth::user();

			$roleId = 3; // role ID of employee

			$query = User::select('users.*')->with(['followers', 'following'])->where('role_id', $roleId);
			//if ($currUser->role_id == 1) {
				$query = $query->where('id', '!=', Auth::user()->id);
			/*} else*/ if ($currUser->role_id > 1) {
				$query = $query->where('company_id', $currUser->company_id);
			}

			$res = $query->orderBy('id', 'desc');
			return Datatables::of($res)->addColumn('role', function ($row) {
				return 'Employee';
			})->addColumn('points', function ($row) {
				$points = Helpers::user_points($row->id);
				return '<p>' . $points['points'] . '</p>';
			})->addColumn('name', function ($row) {
                                if(Auth::user()->role_id != 3) {
                                    return '<label class="check"><input type="checkbox" name="user_id[]" value="' . $row->id . '" class="checkbox"><span class="checkmark"></span><a href="'.url('view_profile', Helpers::encode_url($row->id)).'">' . $row->name . '</a></label>';
                                } else {
                                    return '<label><a href="'.url('view_profile', Helpers::encode_url($row->id)).'">' . $row->name . '</a></label>';
                                }
			})->addColumn('email', function ($row) {
				return '<p>' . $row->email . '</p>';
			})->addColumn('following_count', function ($row) {
				return count($row->following);
			})->addColumn('followers_count', function ($row) {
				return count($row->followers);
			})->addColumn('actions', function ($row) {
                            $edit_url = route('user.edit', Helpers::encode_url($row->id));
                            if(Auth::user()->role_id != 3) {
				return '<a href="'.$edit_url.'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                            }
                            else {
                                //return true;
                            }
			})->filter(function ($query) use ($request) {
				if ($request->has('search_query') && !empty(trim($request->get('search_query')))) {
					$query->where('name', 'like', "%{$request->get('search_query')}%")->orWhere('email', 'like', "%{$request->get('search_query')}%");
				}
			})->rawColumns(['name', 'email', 'following_count', 'followers_count', 'points','actions'])->make(true);
			/*
				->addColumn('actions', function ($row) {
								return '<a href="' . route('user.edit', [Helpers::encode_url($row->id)]) . '" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
							})->rawColumns(['actions'])
			*/
		}
	}

	public function getOtherManagerList(Request $request) {
		$query = User::with(['followers', 'following'])->where('role_id', 2);
                if(Auth::user()->role_id > 1) {
                    $query->where('company_id',Auth::user()->company_id);
                }
		if ($request->input('search') && !empty($request->input('search'))) {
                    $query = $query->where(function ($q) use ($request) {
                            $q->where('name', 'like', "%{$request->input('search_query')}%")->orWhere('email', 'like', "%{$request->input('search_query')}%");
                    });
		}
		$res = $query->where('id','!=',Auth::user()->id)->orderBy('id', 'desc');
		return Datatables::of($res)->addColumn('position', function ($row) {
			return 'Company Manager';
		})->addColumn('points', function ($row) {
			$points = Helpers::user_points($row->id);
			return '<p>' . $points['points'] . '</p>';
		})->addColumn('name', function ($row) {
                    if(Auth::user()->role_id != 3) {
			return '<label class="check"><a href="'.url('view_profile', Helpers::encode_url($row->id)).'">' . $row->name . '</a><input type="checkbox" name="user_id[]" value="' . $row->id . '"  class="checkbox"><span class="checkmark"></span></label>';
                    }else {
                        return '<label><a href="'.url('view_profile', Helpers::encode_url($row->id)).'">' . $row->name . '</a></label>';
                    }
		})->addColumn('email', function ($row) {
			return '<p>' . $row->email . '</p>';
		})->addColumn('following_count', function ($row) {
			return count($row->following);
		})->addColumn('followers_count', function ($row) {
			return count($row->followers);
		})->addColumn('role', function ($row) {
			return 'Company Manager';
		})->addColumn('actions', function ($row) {
                    if(Auth::user()->role_id != 3) {
                            $edit_url = route('user.edit', Helpers::encode_url($row->id));
				return '<a href="'.$edit_url.'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                    }else { }
			})->rawColumns(['name', 'email', 'following_count', 'followers_count', 'points','actions'])->make(true);
	}

	public function getEmployeeGrid(Request $request) {
                //DB::connection()->enableQueryLog();
		$query = User::select('users.*')->with(['followers'=>function($q) {
                                    $q->where(['sender_user_id'=>Auth::user()->id,'status'=>1]);
                                }, 'following'])->withCount('followers')->where('role_id', 3);
                $query = $query->where('id','!=',Auth::user()->id);   
                if (Auth::user()->role_id > 1) {
			$query = $query->where('company_id', Auth::user()->company_id);
		}
		if ($request->has('search') && !empty($request->input('search'))) {
                    $search_text = $request->input('search');
                    $query->whereNested(function($q) use ($search_text) {
                        $q->where('name', 'like', '%' . $search_text . '%');
                        $q->orWhere('email', 'like', '%' . $search_text . '%');
                    });
			//$query = $query->Where('name', 'like', "%{$request->input('search')}%")->orWhere('email', 'like', "%{$request->input('search')}%");
		}
		$offset = 0;
		if ($request->has('offset')) {
			$offset = $request->input('offset');
		}

		// if ($request->has('search_query')) {
		// 	$query = $query->orderBy('id', 'desc')->get();
		// }
		$users_count = count($query->get());
		$users = $query->offset($offset)->limit(POST_DISPLAY_LIMIT)->orderBy('id', 'desc')->get()->toArray();
                //dd(DB::getQueryLog());
		// dd($users);
		$html = view::make($this->folder . '.users.ajax_employee', compact('users'));
		$output = array('html' => $html->render(), 'count' => $users_count);
		return $output;
	}
	public function getOtherManagerGrid(Request $request) {
                //DB::connection()->enableQueryLog();
		$offset = 0;
		if ($request->has('offset')) {
			$offset = $request->input('offset');
			// return $offset;
		}
		$query = User::with(['followers'=>function($q) {
                                    $q->where(['sender_user_id'=>Auth::user()->id,'status'=>1]);
                                }, 'following'])->withCount('followers')->where('role_id', 2);
                if(Auth::user()->role_id > 1) {
                    $query->where('company_id',Auth::user()->company_id)->where('id','!=',Auth::user()->id);
                }                
		if ($request->input('search') && !empty($request->input('search'))) {
			$query = $query->where(function ($q) use ($request) {
				$q->where('name', 'like', "%{$request->input('search')}%")->orWhere('email', 'like', "%{$request->input('search')}%");
			});
		}
                
		$users_query = $query->orderBy('id', 'desc');
		$users_count = count($users_query->get());
		$users = $users_query->offset($offset)->limit(POST_DISPLAY_LIMIT)->get()->toArray();
                //dd(DB::getQueryLog());
		$html = view::make($this->folder . '.users.ajax_other_managers', compact('users'));
		$output = array('html' => $html->render(), 'count' => $users_count);
		return $output;
	}

	public function getGroupAdminGrid(Request $request) {
                //DB::connection()->enableQueryLog();
		$groups_query = GroupUser::where('is_admin', 1);
                if (Auth::user()->role_id > 1) {
                    $groups_query->where('company_id', Auth::user()->company_id);
                }
		$groups = $groups_query->groupBy('group_id')->get();
                // $group_owner = $groups->pluck('group_owner')->toArray();
		$group_ids = $groups->pluck('group_id')->toArray();
                $users_query = GroupUser::with(['userDetail'=>function($q) {$q->withCount('followers');}, 'userDetail.followers'=>function($q) {
                                    $q->where(['sender_user_id'=>Auth::user()->id,'status'=>1]);
                                }, 'userDetail.following']);
                $users_query = $users_query->where('user_id','!=',Auth::user()->id);                
                if (Auth::user()->role_id > 1) {
			$users_query = $users_query->where('company_id', Auth::user()->company_id);
		}
		if ($request->has('search') && !empty($request->input('search'))) {
			$users_query = $users_query->where(function ($q) use ($request) {
				$q->where('name', 'like', "%{$request->input('search')}%")->orWhere('email', 'like', "%{$request->input('search')}%");
			});
		}
		$users = $users_query->where('is_admin', 1)->whereIn('group_id', $group_ids)->get()->toArray();
                //dd($users);
                //dd(DB::getQueryLog());
		$html = view::make($this->folder . '.users.ajax_admin', compact('users'));
		$output = array('html' => $html->render(), 'count' => count($users));
		return $output;
	}

	public function getGroupAdminList(Request $request) {
                 DB::connection()->enableQueryLog();
		$group_admins_query = User::with(['following', 'followers'])->select(DB::raw('users.name, users.id, roles.role_name ,GROUP_CONCAT(groups.group_name SEPARATOR ", ") as group_admins'));
                if (Auth::user()->role_id > 1) {
			$group_admins_query = $group_admins_query->where('users.company_id', Auth::user()->company_id);
		}
		if ($request->has('search_query') && !empty($request->input('search_query'))) {
			$group_admins_query = $group_admins_query->where(function ($q) use ($request) {
				$q->where('name', 'like', "%{$request->input('search_query')}%")->orWhere('email', 'like', "%{$request->input('search_query')}%");
			});
		}
                
		$group_admins = $group_admins_query->leftJoin('group_users', 'group_users.user_id', '=', 'users.id')->leftJoin('groups', 'groups.id', '=', 'group_users.group_id')->leftJoin('roles', 'roles.id', '=', 'users.role_id')->where('group_users.is_admin', '1')->where('users.id','!=',Auth::user()->id)->groupBy('users.id')->get();
                //dd(DB::getQueryLog());
		return Datatables::of($group_admins)->addColumn('role', function ($row) {
			return '<p>' . $row->role_name . '</p>';
		})->addColumn('points', function ($row) {
			$points = Helpers::user_points($row->id);
			return '<p>' . $points['points'] . '</p>';
		})->addColumn('name', function ($row) {
                     if(Auth::user()->role_id != 3) {
			return '<label class="check"><a href="'.url('view_profile', Helpers::encode_url($row->id)).'">' . $row->name . '</a><input type="checkbox" name="user_id[]" value="' . $row->id . '"  class="checkbox"><span class="checkmark"></span></label>';
                        } else {
                          return '<label><a href="'.url('view_profile', Helpers::encode_url($row->id)).'">' . $row->name . '</a></label>';  
                        }
		})->addColumn('email', function ($row) {
			return '<p>' . $row->email . '</p>';
		})->addColumn('following_count', function ($row) {
			return count($row->following);
		})->addColumn('followers_count', function ($row) {
			return count($row->followers);
		})->addColumn('group_admins', function ($row) {
			return '<p>' . $row->group_admins . '</p>';
		})->addColumn('actions', function ($row) {
                    if(Auth::user()->role_id != 3) {
                    $edit_url = route('user.edit', Helpers::encode_url($row->id));
                        return '<a href="'.$edit_url.'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                    }else { }
                })->rawColumns(['role', 'points', 'name', 'email', 'following_count', 'followers_count', 'group_admins','actions'])->make(true);
	}

	public function alterStatus(Request $request) {
		// return $request->all();

		$users_ids = $request->input('users');
		$users_ids = explode(',', $users_ids);
		$data = ['status' => 0, 'msg' => "Please try again later.", 'data' => []];

		if (count($users_ids) > 0) {
			DB::beginTransaction();
			try {
				$currUser = Auth::user();
				$status = 0;
				$str = '';
				$updateData = ['is_active' => 0, 'is_suspended' => 0];
				if ($request->input('action') == 'active') {
					$str = 'activated';
					$updateData = ['is_active' => 1, 'is_suspended' => 0];
				} else if ($request->input('action') == 'inaactive') {
					$str = 'inactivated';
					$updateData = ['is_active' => 0, 'is_suspended' => 0];
				} else if ($request->input('action') == 'suspend') {
					$str = 'suspended';
					$updateData = ['is_active' => 0, 'is_suspended' => 1];
				}
				foreach ($users_ids as $user) {

					User::where('id', $user)->update($updateData);
					// return response()->json($posts);
				}

				if ($status == 0) {
					DB::commit();

					$data = ['status' => 1, 'msg' => 'Users has been ' . $str . ' successfully.', 'data' => []];
				} else if ($status == 1) {
					DB::rollBack();
					$data = ['status' => 0, 'msg' => 'Please try again later..', 'data' => []];
				}
				return response()->json($data);
			} catch (Exception $ex) {
				DB::rollBack();
				$data = ['status' => 0, 'msg' => $ex->getMessage(), 'data' => []];
				return response()->json($data);
			}
		}
		return response()->json($data);
	}
}