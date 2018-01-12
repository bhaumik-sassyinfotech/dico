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
//            if (Auth::user()->role_id == 3) {
			//                return redirect('/index');
			//            }
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
			$usercompany = Company::whereNull('deleted_at')->where('id', $company_id)->first();
			if ($usercompany) {
				if ($usercompany->allow_add_admin == 1) {
					$role_id = [2, 3];
				} else {
					$role_id = [3];
				}
				$roles = Role::whereIn('id', $role_id)->get();
				$companies = Company::whereNull('deleted_at')->get();
				$users = User::with(['following', 'followers'])->where('role_id', 3)->get();
				$company_admins = User::with(['following', 'followers'])->where('role_id', 2)->get();

				return view($this->folder . '.users.index', compact('roles', 'companies', 'users', 'company_admins'));
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

		if (Auth::user()) {
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
			}
		} else {
			return redirect('/index');
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
			$this->validate($request, [
				'user_name' => 'required',
				'user_email' => 'required|email|unique:users,email',
				'company_id' => 'required',
				'role_id' => 'required',
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
			$query = User::with('company')->select('*', DB::raw('CASE WHEN is_active = "1" THEN "Yes" ELSE "No" END AS active,CASE WHEN is_suspended = "1" THEN "Yes" ELSE "No" END AS suspended,CASE WHEN role_id = "2" THEN "Admin" WHEN role_id = "3" THEN "Employee" END AS role'));
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
			$company_id = $user->company_id;
			$usercompany = Company::whereNull('deleted_at')->where('id', $company_id)->first();
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
		try {
			$this->validate($request, [
				'user_name' => 'required',
				'user_email' => 'required|email|unique:users,email,' . $id,
//                'company_id' => 'required',
				'role_id' => 'required',
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
			DB::beginTransaction();
			$user = User::find($id);
			$company_id = $user->company_id;

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

			dd([$request->input('user_groups'), $removed, $new_added]);
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

	public function test() {

		$emailTemplate = Helpers::getEmailTemplateBySlug('NEW_USER');
//        echo $emailTemplate->email_body;die;
		if ($emailTemplate != null) {
			$randomString = str_random(10);
			$parse = [
				'NAME' => 'Bhaumik Mehta',
				'PASSWORD' => $randomString,
//                    'HASH'     => bcrypt($randomString) ,
			];
			$subject = $emailTemplate->subject;
			$parsedTemplate = Helpers::parse_template($emailTemplate->email_body, $parse, $emailTemplate->allowed_fields);
			$message = $parsedTemplate['data'];

			$message = html_entity_decode($message);
			$adminEmail = env('MAIL_FROM_ADDRESS');
			$mailData = array('to' => env('ADMIN_MAIL'),
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

}
