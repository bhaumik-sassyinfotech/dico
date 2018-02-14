<?php

namespace App\Http\Controllers;

use App\Company;
use App\Group;
use App\GroupUser;
use App\Post;
use App\User;
use Auth;
use Carbon\Carbon;
use Config;
use DB;
use Exception;
use Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Response;
use Validator;
use View;
use Yajra\DataTables\Facades\DataTables;

class GroupController extends Controller {

	/**

	 * Display a listing of the resource.

	 *

	 * @return \Illuminate\Http\Response

	 */

	public $folder;

	public function __construct(Request $request) {

		$this->middleware(function ($request, $next) {

			$url_segments = $request->segments();

//                dd($url_segments);

			// if (Auth::user()->role_id == 3) {

			// 	return redirect('/index');

			// }

			if (Auth::user()->role_id == 1) {

				$this->folder = 'superadmin';

			} else if (Auth::user()->role_id == 2) {

				$this->folder = 'companyadmin';

				if (isset($url_segments[1])) {

//                        dd("yes");

					$msg = '';

					if ($url_segments[1] == 'create') {

						$msg = "You don't have permissions to create a group.";

					}

					if (!empty($msg)) {

						return back()->with('err_msg', $msg);

					}

				}

			} else if (Auth::user()->role_id == 3) {

				$this->folder = 'employee';

			}

			return $next($request);

		});

	}

	public function index() {

		//

		$currUser = Auth::user();
		$grp_concat = implode('|', Group::all()->pluck('id')->toArray());
		$groups_query = Group::select('groups.*', DB::raw('(SELECT count(posts.id) FROM posts WHERE FIND_IN_SET(groups.id,posts.group_id) AND posts.deleted_at is null) as group_posts_count'))->withCount(['groupUsers'])->orderBy('id', 'DESC');

		if ($currUser->role_id != 1) {
			$groups_query = $groups_query->where('company_id', $currUser->company_id);
		}

		$groups_count = $groups_query->count();

		$groups = $groups_query->limit(POST_DISPLAY_LIMIT)->get();
		// dd($groups);
		return view($this->folder . '.groups.index', compact('groups', 'groups_count'));

//        return redirect()->route('group.create');

	}

	/**

	 * Show the form for creating a new resource.

	 *

	 * @param Request $request

	 *

	 * @return \Illuminate\Http\Response

	 */

	public function create(Request $request) {

		//

		$companies = Company::all();

		return view($this->folder . '.groups.create', compact('companies'));

	}

	/**

	 * Store a newly created resource in storage.

	 *

	 * @param  \Illuminate\Http\Request $request

	 *

	 * @return \Illuminate\Http\Response

	 */

	public function store(Request $request) {

		$validator = $this->validation($request);

		if ($validator->fails()) {

			return redirect()->back()->withErrors($validator)->withInput();

		}

		DB::beginTransaction();

		try

		{

			$user = Auth::user();

			$group = new Group;

			$group->group_name = $request->input('group_name');

			$group->description = $request->input('group_description');

			$group->company_id = $request->input('company_listing');

			$group->group_owner = $request->input('group_owner');

			$group->created_by = $user->id;

			$group->updated_by = $user->id;

			if ($group->save()) {

				if (!empty($request->users_listing)) {

					$users = $request->users_listing;

					$company = $request->company_listing;

					$now = Carbon::now();

					$insertData = [['group_id' => $group->id, 'user_id' => $request->group_owner, 'company_id' => $company, 'is_admin' => 1, 'created_at' => $now, 'updated_at' => $now]];

					foreach ($users as $k => $v) {

						$insertData[] = ['group_id' => $group->id, 'user_id' => $v, 'company_id' => $company, 'is_admin' => 0, 'created_at' => $now, 'updated_at' => $now];

					}

					$result = GroupUser::insert($insertData);

					if ($result) {

						DB::commit();

						return redirect()->route('group.index')->with('success', "Group " . Config::get('constant.CREATED_MESSAGE'));

					}

				}

			}

		} catch (Exception $ex) {

			DB::rollBack();

			return redirect()->route('group.index')->with('err_msg', $ex->getMessage());

		}

	}

	/**

	 * Display the specified resource.

	 *

	 * @param  \App\Group $group

	 *

	 * @return \Illuminate\Http\Response

	 */

	public function show(Group $group) {

		//

	}

	/**

	 * Show the form for editing the specified resource.

	 *

	 * @param  \App\Group $group

	 *

	 * @return \Illuminate\Http\Response

	 */

	public function edit($id, Request $request) {

		$id = Helpers::decode_url($id);

		// dd($id);

		$groupId = $id;

		$companies = Company::all();

		$groupData = Group::with(['groupUsers', 'groupUsers.userDetail', 'groupUsers.followers', 'groupUsers.following'])->where('id', $id)->first();

		$userPosts = Post::with(['postLike', 'postComment', 'postTag', 'postUser'])->where('group_id', $groupId)->get();

		$count['admins'] = count(GroupUser::where('is_admin', '1')->where('group_id', $groupId)->get()->toArray());

		$count['total_users'] = $groupData->groupUsers->count();

		$count['total_posts'] = $userPosts->count();

//            dd($groupData->groupUsers->pluck('user_id')->toArray());

		$currUserIsAdmin = 1;

		$admin = GroupUser::where('is_admin', '1')->where('user_id', Auth::user()->id)->where('group_id', $groupId)->first();

		if (is_null($admin) && Auth::user()->id != $groupData->group_owner) {

			$currUserIsAdmin = 0;

		}

		$groupUsers = $groupData->groupUsers->pluck('user_id')->toArray();

		$companyEmployee = User::where('company_id', $groupData->company_id)->where('role_id', '!=', 1)->whereNotIn('id', $groupUsers)->get();

		// dd($userPosts);

		return view($this->folder . '.groups.edit', compact('groupData', 'count', 'companies', 'companyEmployee', 'groupId', 'userPosts', 'currUserIsAdmin'));

	}

	/**

	 * Update the specified resource in storage.

	 *

	 * @param  \Illuminate\Http\Request $request

	 * @param  \App\Group               $group

	 *

	 * @return \Illuminate\Http\Response

	 */

	public function update($groupId, Request $request) {

		//

		$validator = $this->validation($request);

		if ($validator->fails()) {

			return redirect()->back()->withErrors($validator)->withInput();

		}

		$data = ['msg' => 'Please try again.', 'status' => 0];

		$group = Group::find($groupId);

		$group->group_name = $request->group_name;

		$group->description = $request->group_description;

		if ($group->save()) {

			$data['msg'] = 'Group details has been updated successfully.';

			$data['status'] = 1;

		}

		return redirect(route('group.index'))->with(['success' => $data['msg'], $data]);

	}

	/**

	 * Remove the specified resource from storage.

	 *

	 * @param  \App\Group $group

	 *

	 * @return \Illuminate\Http\Response

	 */

	public function destroy(Group $group) {

		//

	}

	/*Fetch users of company via ajax call*/

	public function companyUsers(Request $request) {

		$data = ['msg' => 'please try again later.', 'success' => '0', 'data' => []];

		if ($request->ajax()) {

			$companyId = $request->input('company_id');

			$query = User::where('company_id', $companyId)->where('role_id', '!=', 1)->where('is_active', '1');

			if ($request->input('group_owner')) {

				$query = $query->where('id', '!=', $request->input('group_owner'));

			}

			$users = $query->get();

			return Response::json(array(

				'success' => '1',

				'data' => $users,

				'msg' => 'Success',

			));

		}

		return $data;

	}

	public function groupListing(Request $request) {

		$currUser = Auth::user();

		// DB::statement(DB::raw('set @rownum=0'));

		$group_query = '';

		$group_query = Group::select('*', DB::raw('( SELECT count(posts.id) FROM posts WHERE FIND_IN_SET(groups.id,posts.group_id) AND posts.deleted_at is null) as group_posts_count'))->withCount(['groupUsers']);

		if ($currUser->role_id != '1') {

			$group_query = $group_query->where('company_id', $currUser->company_id);

		}

		if ($request->has('search_text') && !empty($request->input('search_text'))) {

			// $group_query = $group_query->where('group_name', 'like', "%{$request->input('search_text')}%")

			// 	->where('description', 'like', "%{$request->input('search_text')}%");

			$group_query = $group_query->where(function ($q) use ($currUser, $request) {

				$q->where('group_name', 'like', "%{$request->input('search_text')}%")

					->orWhere('description', 'like', "%{$request->input('search_text')}%");

			});

		}

		$group = $group_query->orderBy('id', 'DESC')->get();

		// dd($group);

		return Datatables::of($group)->addColumn('group_name', function ($row) {

			$id = Helpers::encode_url($row->id);

			$raw_id = $row->id;

			return '<label class="check"><p><a href="' . route('group.edit', [$id]) . '" >' . $row->group_name . '</a></p><input name="group_id[]" class="checkbox" value="' . $raw_id . '" type="checkbox"><span class="checkmark"></span></label>';

		})->addColumn('description', function ($row) {

			return '<p class="width">' . $row->description . '</p>';

		})->addColumn('group_posts_count', function ($row) {

			return '<p>' . $row->group_posts_count . '</p>';

		})->addColumn('group_users_count', function ($row) {

			return '<p>' . $row->group_users_count . '</p>';

		})->addColumn('actions', function ($row) use ($currUser) {

			$addUserBtn = '';

			$editBtn = '<a class="left-10" href="' . route('group.edit', [Helpers::encode_url($row->id)]) . '" title="Edit" ><i class="fa fa-pencil"></i></a>';

			if ($row->group_owner == $currUser->id) {

				$addUserBtn = ' | <a class="left-10" href="javascript:void(0);" data-group-id="' . $row->id . '" data-company-id="' . $row->company_id . '" title="Edit" class="addUserToGroup" ><i class="fa fa-user"></i></a>';

			}

			return '<p>' . $editBtn . $addUserBtn . '</p>';

		})->rawColumns(['actions', 'group_name', 'description', 'group_users_count', 'group_posts_count'])->make('true');

	}

	public function groupUsersEdit(Request $request) {

		$responseData = ['msg' => 'please try again.', 'status' => 0, 'data' => []];

		$query = $users = $groupUsers = $groupUserId = [];

		$company_id = $request->get('company_id');

		$company = Company::find($company_id);

		$group_id = $request->get('group_id');

		$groupUserId = $request->get('groupUserId');

		$groupUser = GroupUser::find($groupUserId);

		$groupDetails = Group::where('id', $group_id)->first();

		DB::statement(DB::raw('set @rownum=0'));

		$groupUsers = GroupUser::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'group_users.*'])->with(['userDetail', 'followers', 'following'])->where('group_id', $group_id);

		if ($request->get('makeAdmin') == 1) {

			$groupUser->is_admin = 1;

			if ($groupUser->save()) {

				return Response::json(['msg' => 'User has been promoted to group admin', 'status' => 1]);

			}

		} else if ($request->get('removeAsAdmin') == 1) {

			$groupUser->is_admin = 0;

			if ($groupUser->save()) {

				return Response::json(['msg' => 'User has been relegated', 'status' => 1]);

			}

		} else if ($request->get('removeFromGroup') == 1) {

			if ($groupUser->delete()) {
				return Response::json(['msg' => 'User has been removed from the group.', 'status' => 1]);

			} else {

				return Response::json(['msg' => 'There has been some error removing user.', 'status' => 1]);

			}

		} else if ($request->get('getGroupUsers') == 1) {

			$groupUserIds = GroupUser::where('group_id', $group_id)->get()->pluck('user_id')->prepend(1)->toArray(); // select user_id which are already in the group

			$users = User::whereNotIn('id', $groupUserIds)->where('role_id', '!=', '1')->where('company_id', $company_id)->get()->toArray();
                        $count['admins'] = count(GroupUser::where('is_admin', '1')->where('group_id', $request->get('group_id'))->get()->toArray()) + 1;
                        $groupData = Group::with(['groupUsers', 'groupUsers.userDetail', 'groupUsers.followers', 'groupUsers.following'])->where('id', $request->get('group_id'))->first();
                        $count['total_users'] = $groupData->groupUsers->count();
			return Response::json(['msg' => 'Users listing.', 'data' => $users, 'status' => 1,'count'=>$count]);

		} else if ($request->get('addGroupUsers') == 1) {

			$users_list = $request->get('users_list');

			$insertData = [];

			$now = Carbon::now();

			foreach ($users_list as $user) {

				$insertData[] = ['user_id' => $user, 'group_id' => $group_id, 'updated_at' => $now, 'created_at' => $now];

			}

			$result = GroupUser::insert($insertData);

			if ($result) {

				return Response::json(['msg' => 'Users has been added to the group.', 'status' => 1]);

			} else {

				return Response::json(['msg' => 'There was some error adding users to group.', 'status' => 0]);

			}

		}

		$i = 25;
                //$groupData = Group::with(['groupUsers', 'groupUsers.userDetail', 'groupUsers.followers', 'groupUsers.following'])->where('id', $id)->first();

		//$userPosts = Post::with(['postLike', 'postComment', 'postTag', 'postUser'])->where('group_id', $groupId)->get();

		

//            $rowCount = 0;

		return DataTables::of($groupUsers)->addColumn('admin', function ($row) use ($groupDetails, $company) {

			$btn = '';
            $admin = '';
			if ($row->user_id == $groupDetails->group_owner) {

				return "<p><a class='deactive-admin'>Group Owner</a></p>";

			} else {

				if ($company->allow_add_admin == '1') {

					if ($row->is_admin == 1) {

						$admin = '<p><a href="#" data-group-user-id="' . $row->id . '" class="active-admin demoteToUser">Promote to admin</a>';

						$admin .= ' <a data-group-user-id="' . $row->id . '" style="padding: 7px 10px; display: inline-block; line-height: 135%; text-align: left;" class="left-10" href="#"><img src="' . asset('assets/img/icon-delete.png') . '" alt="icon delete"></a></p>';

					} else {

						$admin = '<p><a href="#" data-group-user-id="' . $row->id . '" class=" promoteToAdmin deactive-admin">Promote to admin</a>';

						$admin .= ' <a data-group-user-id="' . $row->id . '" style="padding: 7px 10px; display: inline-block; line-height: 135%; text-align: left;" class=" removeUser left-10" href="#"><img src="' . asset('assets/img/icon-delete.png') . '" alt="icon delete"></a></p>';

					}

				} else {

//                        $admin = ' <a href="#" data-group-user-id="' . $row->id . '" class="btn btn-danger removeUser"><i class="fa fa-trash-o"></i></a></p>';
					$admin = ' <p><a data-group-user-id="' . $row->id . '" style="padding: 7px 10px; display: inline-block; line-height: 135%; text-align: left;" class="left-10 removeUser" href="#"><img src="' . asset('assets/img/icon-delete.png') . '" alt="icon delete"></a></p>';
				}

			}

			return $admin;

		})->addColumn('detail', function ($row) {

			$url = url('view_profile/' . Helpers::encode_url($row->userDetail->id));

			return '<p class="blue"><a href="' . $url . '">' . $row->userDetail->name . '</a><span>' . $row->userDetail->email . '</span></p>';

		})->addColumn('following', function ($row) {

			return '<p >' . count($row->following) . '<span></span></p>';

		})->addColumn('followers', function ($row) {

			return '<p >' . count($row->followers) . '<span></span></p>';

		})->addColumn('points', function ($row) use (&$i) {

			$points = Helpers::user_points($row->userDetail->id);

			return '<p>' . $points['points'] . '<span></span></p>';

		})->addColumn('action', function ($row) use ($groupDetails) {

			$removeBtn = '';

			if ($row->user_id != $groupDetails->group_owner) {

				$removeBtn = '<p><a href="#" data-group-user-id="' . $row->id . '" class="btn btn-danger removeUser"><i class="fa fa-trash-o"></i></a></p>';

			} else if ($row->user_id == $groupDetails->group_owner) {

				$removeBtn = '<p>Group Owner</p>';

			}

			return $removeBtn;

//

		})->rawColumns(['admin', 'detail', 'action', 'following', 'followers', 'detail', 'points'])->make(TRUE);

	}

	public function validation($request) {

		$rulesArray = [];

//            switch($this->method()) {

		switch ($request->method()) {

		case 'GET':

		case 'DELETE':

			$rulesArray = [];

			break;

		case 'POST':

			$rulesArray = [

				'group_name' => 'required',

				'group_description' => 'nullable',

				'company_listing' => 'required',

				'group_owner' => 'required',

				'users_listing' => 'required',

			];

			break;

		case 'PUT':

		case 'PATCH':

			$rulesArray = [

				'group_name' => 'required',

				'group_description' => 'nullable',

			];

			break;

		default:

			break;

		}

//            return $validator = Validator::make($request->all() , $rulesArray);

		return Validator::make($request->all(), $rulesArray);

	}

	public function uploadGroupPicture(Request $request) {

		$file = $request->file('group_picture');

		$group_id = $request->input('group_id');

		if ($file != "" && !empty($group_id)) {

			$postData = array();

			//echo "here";die();

			$fileName = $file->getClientOriginalName();

			$extension = $file->getClientOriginalExtension();

			$folderName = '/uploads/groups';

			$destinationPath = public_path() . $folderName;

			$safeName = str_random(10) . '.' . $extension;

			$file->move($destinationPath, $safeName);

			$updateData = ['group_image' => $safeName];

			Group::where('id', $group_id)->update($updateData);

			return back()->with('success', 'Group picture has been updated successfully.');

		} else {

			return back();

		}

	}

	public function addUserByEmailAddress(Request $request) {

		if ($request->ajax()) {

			$user_email = $request->input('user');

			$company_id = $request->input('company_id');

			$group_id = $request->input('group_id');

			$response = ['status' => 0, 'data' => [], 'msg' => "Please try again later."];

			if (empty($user_email) || empty($company_id) || empty($group_id)) {

				$response = ['status' => 0, 'data' => [], 'msg' => "Field cannot be left empty."];

			} else {

				if (User::where('email', $user_email)->exists()) {

					$user = User::where('email', $user_email)->where('company_id', $company_id)->first();

					if (empty($user)) {

						$response = ['status' => 0, 'data' => [], 'msg' => "User with this email address doesn't exist."];

					} else {

						if (GroupUser::where(['user_id' => $user->id, 'group_id' => $group_id, 'company_id' => $company_id])->exists()) {

							$response = ['status' => 0, 'data' => [], 'msg' => "This user is already present in this group."];

						} else {

							$now = Carbon::now();

							$insertData = ['user_id' => $user->id, 'group_id' => $group_id, 'company_id' => $company_id, 'created_at' => $now, 'updated_at' => $now];

							if (GroupUser::insert($insertData)) {

								$response = ['status' => 1, 'data' => [], 'msg' => "User has been added to the group."];

							} else {

								$response = ['status' => 0, 'data' => [], 'msg' => "Please try again later."];

							}

						}

					}

				} else {

					$response = ['status' => 0, 'data' => [], 'msg' => "User with this email address doesn't exist."];

				}

			}

			return Response::json($response);

		}

	}

	public function myGroupGrid(Request $request) {

		$offset = 0;

		$currUser = Auth::user();

		if ($request->has('offset')) {

			$offset = $request->input('offset');

		}

		// $query = Group::select('groups.*')->withCount(['groupUsers', 'groupPosts']);

		// if ($request->has('search_text') && !empty($request->input('search_text'))) {

		// 	// return $request->input('search_text');

		// 	$query = $query->where('group_name', 'like', "%{$request->input('search_text')}%")

		// 		->orWhere('description', 'like', "%{$request->input('search_text')}%");

		// }

		// if ($currUser->role_id != 1) {

		// 	$query = $query->where('company_id', $currUser->company_id);

		// }

		$group_query = '';

		$group_query = Group::select('groups.*')->withCount(['groupUsers', 'groupPosts']);

		// return $currUser->company_id;

		if ($currUser->role_id != 1) {

			$group_query = $group_query->where('company_id', $currUser->company_id);

		}

		if ($request->has('search_text') && !empty($request->input('search_text'))) {

			// $group_query = $group_query->where('group_name', 'like', "%{$request->input('search_text')}%")

			// 	->where('description', 'like', "%{$request->input('search_text')}%");

			$group_query = $group_query->where(function ($q) use ($currUser, $request) {

				$q->where('group_name', 'like', "%{$request->input('search_text')}%")

					->orWhere('description', 'like', "%{$request->input('search_text')}%");

			});

		}

		$group_count = $group_query->count();

		$groups = $group_query->orderBy('id', 'DESC')->offset($offset)->limit(POST_DISPLAY_LIMIT)->get()->toArray();

		// return $groups;

		$html = view::make($this->folder . '.groups.ajax_mygroups', compact('groups'));

		$output = array('html' => $html->render(), 'count' => $group_count);

		return $output;

	}

	public function deleteGroup(Request $request) {
		$groups_ids = $request->input('groups');
		$groups_ids = explode(',', $groups_ids);
		$data = ['status' => 0, 'msg' => "Please try again later.", 'data' => []];

		if (count($groups_ids) > 0) {
			DB::beginTransaction();
			try {
				$currUser = Auth::user();
				$status = 0;
				foreach ($groups_ids as $group) {

					$current_group_query = Group::where('id', $group);

					$current_group = $current_group_query->first();

					if ($currUser->role_id == 2) {
						if ($currUser->id != $current_group->group_owner) {
							continue;
						}
					}

					$current_group_query->delete();
					GroupUser::where('group_id', $group)->delete();

					$posts_groups = Post::whereRaw("FIND_IN_SET($group,group_id)")->get();

					foreach ($posts_groups as $posts) {
						$post = Post::find($posts->id);

						$old_groups = explode(',', $post->group_id);

						$new_groups = array_diff($old_groups, (array) $group);

						if (empty($new_groups) && count($new_groups) == 0) {
							$new_groups = 0;
						} else {
							$new_groups = implode(',', $new_groups);
						}

						$post->group_id = $new_groups;

						if ($post->save()) {
							$status = 0;
						} else {
							$status = 1;
						}

						if ($status == 1) {
							DB::rollBack();
							break;
						}

					}

					// return response()->json($posts);
				}

				if ($status == 0) {
					DB::commit();
					$data = ['status' => 1, 'msg' => 'Group has been deleted successfully.', 'data' => []];
				} else if ($status == 1) {
					DB::rollBack();
					$data = ['status' => 0, 'msg' => 'Deletion of group failed.', 'data' => []];
				}
				return response()->json($data);
			} catch (Exception $ex) {
				DB::rollBack();
				$data = ['status' => 0, 'msg' => $ex->getMessage(), 'data' => []];
				return response()->json($data);
			}
		}

	}

	public function searchGroup(Request $request) {

		if (Auth::check()) {
			$id = $request->input('user_id');

			$currUser = Auth::user();
			$view_user = User::find($id);
			$company_id = $view_user->company_id;

			$groupDetails_query = DB::table('group_users')
				->join('groups', 'groups.id', '=', 'group_users.group_id')
				->where('groups.company_id', $company_id);

			if ($request->has('search') && !empty($request->input('search'))) {
				$groupDetails_query = $groupDetails_query->where(function ($q) use ($request) {
					$q->where('groups.group_name', 'like', "%{$request->input('search')}%")->orWhere('groups.description', 'like', "%{$request->input('search')}%");
				});
			}

			$groupDetails = $groupDetails_query->select(DB::raw('count(distinct(group_users.user_id)) as total_members, groups.* , (SELECT count(posts.id) FROM posts WHERE FIND_IN_SET(groups.id,posts.group_id) AND posts.deleted_at is null) as total_posts'))
				->groupBy('group_users.group_id')->get();

			$html = view::make($this->folder . '.users.view_profile_ajax', compact('groupDetails'));

			$output = array('html' => $html->render());
			return $output;
		}
	}
	public function addGroup(Request $request) {
//		return $request->all();
		$company_id = $request->input('company');
		$group_name = $request->input('name');
		$group_desc = $request->input('desc');
		$data = ['status' => 0 , 'data' => [] , 'msg' => "Please try again later."];
		if(!empty($company_id) && !empty($group_name))
        {
            
            $group = new Group;
            $group->group_name  = $group_name;
            $group->description = $group_desc;
            $group->company_id = $company_id;
            if($group->save())
            {
//                $grp_user = new GroupUser;
//                $grp_user->user_id =
                $data['status'] = 1;
                $data['msg'] = 'Group created successfully.';
            } else {
                $data['msg'] = 'Creation of group failed.';
            }
        }
        return Response::json($data);

	}
}
