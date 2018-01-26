<?php
namespace App\Http\Controllers;

use App\FollowUser;
use App\GroupUser;
use App\Post;
use App\SecurityQuestion;
use App\User;
use App\UserSecurityQuestion;
use Auth;
use Carbon;
use Config;
use DB;
use Helpers;
use Illuminate\Http\Request;
use Redirect;
use Validator;

class DashboardController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	//protected $table = 'room';
	public $folder;
	public function __construct(Request $request) {
		$this->middleware(function ($request, $next) {
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

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//dd("here");
		return view($this->folder . '.dashboard');
	}
	public function edit_profile(Request $request) {
		if (Auth::user()) {
			$user_id = Auth::user()->id;
			$user = User::with(['followers', 'following'])->where('id', $user_id)->first();
			$questions = SecurityQuestion::all();
			$userquestions = UserSecurityQuestion::where('user_id', $user_id)->get();
			return view($this->folder . '.users.edit_profile', compact('user', 'questions', 'userquestions'));
		} else {
			return redirect('/index');
		}
	}

	public function update_profile_pic(Request $request) {
		$validator = Validator::make($request->all(), [
			'profile_image' => 'required|image|mimes:jpg,png,jpeg',
		]);
		// dd($request->all());
		$user_id = Auth::user()->id;
		$file = $request->file('profile_image');
		$postData = [];
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
	}
	public function update_profile(Request $request) {
		try
		{
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
		} catch (\exception $e) {
			return Redirect::back()->with('err_msg', $e->getMessage());
		}
	}
	public function view_profile($id = null) {
		$id = Helpers::decode_url($id);
		if (Auth::user() && !empty($id)) {
			$currUser = Auth::user();
			$user_id = $id;
			$view_user = User::find($id);
			$company_id = $view_user->company_id;
			$user = User::with(['followers', 'following', 'followers.followUser', 'following.followingUser'])->where('id', $user_id)->first();
			$group_ids = GroupUser::select('group_id')->where('user_id', $user_id)->pluck('group_id')->toArray();
			// print_r($group_ids);
			// DB::enableQueryLog();
			// $groupDetails = DB::table('group_users')->Join('groups', 'groups.id', '=', 'group_users.group_id')
			// 	->leftJoin('posts', 'groups.id', '=', 'posts.group_id')
			// 	->whereIn('group_users.group_id', $group_ids)->select(DB::raw('count(group_users.user_id) as total_members, count(posts.id) as total_posts , groups.* '))->groupBy('group_users.group_id')->orderByDesc('posts.created_at')->get();
			// dd(DB::getQueryLog());
			$grp_concat = implode("|", $group_ids);
			// dd($grp_concat);

			// $groupDetails = GroupUser::select(DB::raw('count(distinct(group_users.user_id)) as total_members, groups.* '))->leftJoin('groups', 'groups.id', '=', 'group_users.group_id')
			// 	->leftJoin('posts', 'groups.id', '=', 'posts.group_id')
			// 	->whereIn('group_users.group_id', $group_ids)->groupBy('group_users.group_id')->orderByDesc('posts.created_at')->get();

			$groupDetails_query = DB::table('group_users')
				->join('groups', 'groups.id', '=', 'group_users.group_id')
				->where('groups.company_id', $company_id)
				->select(DB::raw('count(distinct(group_users.user_id)) as total_members, groups.* , (SELECT count(posts.id) FROM posts WHERE FIND_IN_SET(groups.id,posts.group_id) AND posts.deleted_at is null) as total_posts'))
				->groupBy('group_users.group_id');
			if ($currUser->role_id != 1) {
				$groupDetails_query = $groupDetails_query->where('groups.company_id', $company_id);
			}
			$groupDetails = $groupDetails_query->get();
			// dd($groupDetails);

			$userPosts = Post::with(['postLike', 'postComment', 'postTag.tag', 'postUser'])
				->whereIn('group_id', $group_ids)->where('user_id', $user_id)->get();

			//$questions = SecurityQuestion::all();
			//$userquestions = UserSecurityQuestion::where('user_id', $user_id)->get();
			return view($this->folder . '.users.view_profile', compact('user', 'groupDetails', 'userPosts'));
		} else {
			return redirect('/index');
		}
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
						return Redirect::back()->with('success', 'Follow successfully.');
					} else {
						return Redirect::back()->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
					}
				} else {
					$follow = new FollowUser;
					$follow->sender_user_id = $user_id;
					$follow->receiver_user_id = $id;
					$follow->status = 1;
					$follow->created_at = Carbon\Carbon::now();
					if ($follow->save()) {
						return Redirect::back()->with('success', 'Follow successfully.');
					} else {
						return Redirect::back()->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
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
					return Redirect::back()->with('success', 'Unfollow successfully.');
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

}
?>
