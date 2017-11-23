<?php
    
    namespace App\Http\Controllers;
    
    use App\Company;
    use App\Group;
    use App\GroupUser;
    use App\User;
    use Auth;
    use Carbon\Carbon;
    use DB;
    use Exception;
    use Illuminate\Http\Request;
    use Config;
    use Response;
    use Yajra\DataTables\Facades\DataTables;
    
    
    class GroupController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public $folder;
        
        public function __construct(Request $request)
        {
            $this->middleware(function ($request , $next) {
                if ( Auth::user()->role_id == 3 )
                {
                    return redirect('/index');
                }
                if ( Auth::user()->role_id == 1 )
                {
                    $this->folder = 'superadmin';
                } else if ( Auth::user()->role_id == 2 )
                {
                    $this->folder = 'companyadmin';
                } else if ( Auth::user()->role_id == 3 )
                {
                    $this->folder = 'employee';
                }
                
                return $next($request);
            });
            
        }
        
        public function index()
        {
            //
            return view($this->folder . '.groups.index');
//        return redirect()->route('group.create');
        }
        
        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            //
//        dd("create");
            $companies = Company::all();
            
            return view($this->folder . '.groups.create' , compact('companies'));
        }
        
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            DB::beginTransaction();
            try
            {
                $user                  = Auth::user();
                $group                 = new Group;
                $group->group_name     = $request->group_name;
                $group->description    = $request->group_description;
                $group->company_id     = $request->company_listing;
                $group->group_owner      = $request->group_owner;
                $group->created_by     = $user->id;
                $group->updated_by     = $user->id;
                if ( $group->save() )
                {
                    if ( !empty($request->users_listing) )
                    {
                        $users      = $request->users_listing;
                        $insertData = [];
                        $company    = $request->company_listing;
                        $now        = Carbon::now();
                        
                        foreach ( $users as $k => $v )
                        {
                            $insertData[] = [ 'group_id' => $group->id , 'user_id' => $v , 'company_id' => $company , 'is_admin' => 0 , 'created_at' => $now , 'updated_at' => $now ];
                        }
                        $result = GroupUser::insert($insertData);
                        if ( $result )
                        {
                            DB::commit();
                            
                            return redirect()->route('group.index')->with('success' , "Group " . Config::get('constant.CREATED_MESSAGE'));
                        }
                    }
                }
            }
            catch ( Exception $ex )
            {
                DB::rollBack();
                
                return redirect()->route('group.index')->with('err_msg' , $ex->getMessage());
            }
            
            
            //
            
        }
        
        /**
         * Display the specified resource.
         *
         * @param  \App\Group $group
         *
         * @return \Illuminate\Http\Response
         */
        public function show(Group $group)
        {
            //
        }
        
        /**
         * Show the form for editing the specified resource.
         *
         * @param  \App\Group $group
         *
         * @return \Illuminate\Http\Response
         */
        public function edit($id , Request $request)
        {
            $groupId         = $id;
            $companies       = Company::all();
            $groupData       = Group::with([ 'groupUsers' ])->where('id' , $id)->first();
//            dd($groupData->groupUsers->pluck('user_id')->toArray());
            $groupUsers      = $groupData->groupUsers->pluck('user_id')->toArray();
            $companyEmployee = User::where('company_id' , $groupData->company_id)->where('role_id','!=',1)->whereNotIn('id' , $groupUsers)->get();
            
            return view($this->folder . '.groups.edit' , compact('groupData' , 'companies' , 'companyEmployee' , 'groupId'));
        }
        
        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  \App\Group               $group
         *
         * @return \Illuminate\Http\Response
         */
        public function update($groupId , Request $request)
        {
            //
            $data               = [ 'msg' => 'Please try again.' , 'status' => 0 ];
            $group              = Group::find($groupId);
            $group->group_name  = $request->group_name;
            $group->description = $request->group_description;
            if ( $group->save() )
            {
                $data[ 'msg' ]    = 'Group details has been updated successfully.';
                $data[ 'status' ] = 1;
            }
            
            return redirect(route('group.index'))->with([ 'success' => $data[ 'msg' ] , $data ]);
        }
        
        /**
         * Remove the specified resource from storage.
         *
         * @param  \App\Group $group
         *
         * @return \Illuminate\Http\Response
         */
        public function destroy(Group $group)
        {
            //
        }
        
        /*Fetch users of company via ajax call*/
        public function companyUsers(Request $request)
        {
            $data = [ 'msg' => 'please try again later.' , 'success' => '0' , 'data' => [] ];
            if ( $request->ajax() )
            {
                $companyId = $request->company_id;
                $users     = User::where('company_id' , $companyId)->where('role_id','!=' , 1)->where('is_active' , '1')->get();
                
                return Response::json(array(
                    'success' => '1' ,
                    'data'    => $users ,
                    'msg'     => 'Success' ,
                ));
            }
            
            return $data;
        }
        
        public function groupListing()
        {
            DB::statement(DB::raw('set @rownum=0'));
            $group = Group::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'groups.*' ])->withCount([ 'groupUsers' ])->orderBy('id','DESC')->get();
//            return $group;
//        return $group;
            return Datatables::of($group)->addColumn('actions' , function ($row) {
                $editBtn = '<a href="' . route('group.edit' , [ $row->id ]) . '" title="Edit" ><i class="fa fa-pencil"></i></a>';
                
                return $editBtn;
            })->rawColumns([ 'actions' ])->make('true');
        }
        
        public function groupUsersEdit(Request $request)
        {
            $responseData = [ 'msg' => 'please try again.' , 'status' => 0 , 'data' => [] ];
            $query        = $users = $groupUsers = $groupUserId = [];
            
            $company_id = $request->get('company_id');
            
            $group_id = $request->get('group_id');
            
            $groupUserId  = $request->get('groupUserId');
            $groupUser    = GroupUser::find($groupUserId);
            $groupDetails = Group::where('id' , $group_id)->first();
            DB::statement(DB::raw('set @rownum=0'));
            $groupUsers   = GroupUser::select([DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'group_users.*' ])->with([ 'userDetail' ])->where('group_id' , $group_id);
            
            if ( $request->get('makeAdmin') == 1 )
            {
                $groupUser->is_admin = 1;
                if ( $groupUser->save() )
                {
                    return Response::json([ 'msg' => 'User has been promoted to group admin' , 'status' => 1 ]);
                }
            } else if ( $request->get('removeAsAdmin') == 1 )
            {
                $groupUser->is_admin = 0;
                if ( $groupUser->save() )
                {
                    return Response::json([ 'msg' => 'User has been relegated' , 'status' => 1 ]);
                }
            } else if ( $request->get('removeFromGroup') == 1 )
            {
                if ( $groupUser->delete() )
                    return Response::json([ 'msg' => 'User has been removed from the group.' , 'status' => 1 ]);
                else
                    return Response::json([ 'msg' => 'There has been some error removing user.' , 'status' => 1 ]);
            } else if ( $request->get('getGroupUsers') == 1 )
            {
                $groupUserIds = GroupUser::where('group_id' , $group_id)->get()->pluck('user_id')->prepend(1)->toArray(); // select user_id which are already in the group
                $users        = User::whereNotIn('id' , $groupUserIds)->where('role_id','!=','1')->where('company_id' , $company_id )->get()->toArray();
                
                return Response::json([ 'msg' => 'Users listing.' , 'data' => $users , 'status' => 1 ]);
            } else if ( $request->get('addGroupUsers') == 1 )
            {
                $users_list = $request->get('users_list');
                $insertData = [];
                $now        = Carbon::now();
                foreach ( $users_list as $user )
                {
                    $insertData[] = [ 'user_id' => $user , 'group_id' => $group_id , 'updated_at' => $now , 'created_at' => $now ];
                }
                
                $result = GroupUser::insert($insertData);
                if ( $result )
                    return Response::json([ 'msg' => 'Users has been added to the group.' , 'status' => 1 ]);
                else
                    return Response::json([ 'msg' => 'There was some error adding users to group.' , 'status' => 0 ]);
            }
            
//            $rowCount = 0;
            
            return DataTables::of($groupUsers)->addColumn('admin' , function ($row) use ($groupDetails) {
                $btn = '';
                if ( $row->user_id == $groupDetails->group_owner )
                {
                    return "Group Owner";
                } else if ( $row->is_admin == 1 )
                {
                    return '<a href="#" data-group-user-id="' . $row->id . '" class="btn btn-danger demoteToUser">Relegate to user</a>';
                } else
                {
                    return '<a href="#" data-group-user-id="' . $row->id . '" class="btn btn-success promoteToAdmin">Promote to admin</a>';
                }
            })->addColumn('action' , function ($row) use ($groupDetails) {
                $removeBtn = '';
                if ( $row->user_id != $groupDetails->group_owner )
                    $removeBtn = '<a href="#" data-group-user-id="' . $row->id . '" class="btn btn-danger removeUser"><i class="fa fa-trash-o"></i></a>';
                
                return $removeBtn;
//
            })->rawColumns([ 'admin' , 'action' ])->make(TRUE);
        }
    }
