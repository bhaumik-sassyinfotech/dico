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
        public function index()
        {
            //
            return view('groups.index');
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
            
            return view('groups.create' , compact('companies'));
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
                $user               = Auth::user();
                $group              = new Group;
                $group->group_name  = $request->group_name;
                $group->description = $request->group_description;
                $group->company_id  = $request->company_listing;
                $group->created_by  = $user->id;
                $group->updated_by  = $user->id;
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
                            
                            return redirect()->route('group.index')->with('success' , "Group " . Config::get('constant.ADDED_MESSAGE'));
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
            $groupUsers      = $groupData->groupUsers->pluck('user_id')->toArray();
            $companyEmployee = User::where('company_id' , $groupData->company_id)->get();
            
            return view('groups.edit' , compact('groupData' , 'groupUsers' , 'companies' , 'companyEmployee' , 'groupId'));
        }
        
        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  \App\Group               $group
         *
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request , Group $group)
        {
            //
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
                $users     = User::where('company_id' , $companyId)->where('is_active' , '1')->get();
                
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
            $rowCount = 0;
            
            $group = Group::select([ 'groups.*' ])->withCount([ 'groupUsers' ])->orderBy('id' , 'DESC');

//        return $group;
            return Datatables::of($group)->addColumn('row' , function ($row) use (&$rowCount) {
                $rowCount += 1;
                
                return $rowCount;
            })->addColumn('actions' , function ($row) {
                $editBtn = '<a href="' . route('group.edit' , [ $row->id ]) . '" title="Edit" ><i class="fa fa-pencil"></i></a>';
                
                return $editBtn;
            })->rawColumns([ 'actions' ])->make('true');
        }
        
        public function groupUsersEdit(Request $request)
        {
            $responseData = [ 'msg' => 'please try again.' , 'status' => 0 , 'data' => [] ];
            $query        = $users = $groupUsers = $groupUserId = [];
//            $company_id = $request->get('company_id')=1;
            $company_id = 1;
//            $group_id = $request->get('group_id')=25;
            $group_id = $request->get('group_id');
//            dd($group_id);
            $groupUserId = $request->get('groupUserId');
            
            $groupUser = GroupUser::find($groupUserId);
            if ( $request->get('makeAdmin') == 1 )
            {
                $groupUser->is_admin = 1;
                if($groupUser->save())
                {
                    return Response::json([ 'msg' => 'User has been promoted to group admin' , 'status' => 1]);
                }
            } else if ( $request->get('removeAsAdmin') == 1 )
            {
                $groupUser->is_admin = 0;
                if($groupUser->save())
                {
                    return Response::json([ 'msg' => 'User has been relegated' , 'status' => 1]);
                }
            } else if ( $request->get('removeFromGroup') == 1 )
            {
                if($groupUser->delete())
                {
                    return Response::json([ 'msg' => 'User has been deleted.' , 'status' => 1]);
                }
            }
//            return $query;
            $groupUsers  = GroupUser::select('group_users.*')->with(['userDetail'])->where('group_id' , 25)->where('company_id' , $company_id)->get();
            
            $rowCount    = 0;
            
            return DataTables::of($groupUsers)->addColumn('row' , function ($row) use (&$rowCount) {
                $rowCount += 1;
                
                return $rowCount;
            })->addColumn('admin' , function ($row)  {
                $btn = '';
                if ( $row->is_admin == 1 )
                {
                    return '<a href="#" data-group-user-id="' . $row->id . '" class="btn btn-danger demoteToUser">Relegate to User</a>';
                } else
                {
                    return '<a href="#" data-group-user-id="' . $row->id . '" class="btn btn-success promoteToAdmin">Promote to Admin</a>';
                }
//                    $makeAdmin = '<a href="#" data-user-id="'++'"
            })->addColumn('action' , function ($row) {
                
                $removeBtn = '<a href="#" data-group-user-id="' . $row->id . '" class="btn btn-danger removeUser">Remove</a>';
                
                return $removeBtn;
//                    $makeAdmin = '<a href="#" data-user-id="'++'"
            })->rawColumns([ 'admin' , 'action' ])->make(TRUE);
        }
    }
