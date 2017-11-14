<?php

namespace App\Http\Controllers;

use App\Company;
use App\Group;
use App\GroupUser;
use App\User;
use Carbon\Carbon;
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
        return view('groups.create',compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $group                    = new Group;
        $group->group_name        = $request->group_name;
        $group->description = $request->group_description;
        if($group->save())
        {
            if(!empty($request->users_listing))
            {
                $users = $request->users_listing;
                $insertData = [];
                $company = $request->company_listing;
                $now = Carbon::now();
                foreach ($users as $k => $v)
                {
                    $insertData[] = [ 'group_id' => $group->id , 'user_id' => $v , 'company_id' => $company , 'is_admin' => 0 , 'created_at' => $now , 'updated_at' => $now];
                }
                $result = GroupUser::insert($insertData);
                if($result)
                {
                    return redirect()->route('group.index')->with('success',"Group".Config::get('constant.ADDED_MESSAGE'));
                }
            }
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }
    
    /*Fetch users of company via ajax call*/
    public function companyUsers(Request $request)
    {
        $data = ['msg' => 'please try again later.' , 'success' => '0' , 'data' => []];
        if($request->ajax())
        {
            $companyId = $request->company_id;
            $users = User::where('company_id',$companyId)->where('is_active' ,'1')->get();
    
            return Response::json(array(
                'success' => '1' ,
                'data'    => $users ,
                'msg' => 'Success'
            ));
        }
        return $data;
    }
    
    public function groupListing()
    {
        $group = Group::select('groups.*')->with(['groupUsers','groupUsersCount'])->get();
//        return $group;
        return Datatables::of($group)->make('true');
    }
}
