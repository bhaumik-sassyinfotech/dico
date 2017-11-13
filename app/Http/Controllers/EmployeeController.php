<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Company;
use DB;
use Validator;
use Redirect;
use Config;
use Carbon;
use Auth;
use Yajra\Datatables\Datatables;


class EmployeeController extends Controller {
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //protected $table = 'room';
    public function __construct(Request $request)
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd("here");
        
       // dd(Auth::user()->company_id);
        return view('employee.index');
    }
    
    public function create() {
        $roles = Role::whereIn('id',[2,3])->get();
        $company = Company::where('id',Auth::user()->company_id)->first();
        return view('employee.create',compact('roles','company'));
    }

    public function store(Request $request) {
         
        $this->validate($request, [
            'employee_name' => 'required',
            'employee_email' => 'required|email|unique:users,email',
            'role_id' => 'required'
        ]);
        if($request->input('is_active')) {
            $is_active = 1;
        } else {
            $is_active = 0;
        }
        if($request->input('is_suspended')) {
            $is_suspended = 1;
        } else {
            $is_suspended = 0;
        }
        
        $employee = new User;
        $employee->name = $request->input('employee_name');
        $employee->email = $request->input('employee_email');
        $employee->role_id = $request->input('role_id');
        $employee->company_id = $request->input('company_id');
        $employee->is_active = $is_active;
        $employee->is_suspended = $is_suspended;
        $employee->created_at = Carbon\Carbon::now();
        if ($employee->save()) {
            return redirect()->route('employee.index')->with('success', 'Employee '.Config::get('constant.ADDED_MESSAGE'));
        } else {
           return redirect()->route('employee.index')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
        }
    }
    
    public function edit($id) {
        $employee = User::where('id',$id)->first();
        $roles = Role::whereIn('id',[2,3])->get();
        $company = Company::where('id',Auth::user()->company_id)->first();
        return view('employee.edit', compact('employee','roles','company'));
    }
    
    public function update(Request $request, $id) {
        $this->validate($request, [
            'employee_name' => 'required',
            'employee_email' => 'required|email|unique:users,email,'.$id,
            'role_id' => 'required'
        ]);
        if($request->input('is_active')) {
            $is_active = 1;
        } else {
            $is_active = 0;
        }
        if($request->input('is_suspended')) {
            $is_suspended = 1;
        } else {
            $is_suspended = 0;
        }
        $postData = array('name'=>$request->input('employee_name'),'email'=>$request->input('employee_email'),'role_id'=>$request->input('role_id'),'company_id'=>$request->input('company_id'),'is_active'=>$is_active,'is_suspended'=>$is_suspended,'updated_at'=>Carbon\Carbon::now());
        $res = User::where('id', $id)->update($postData);
        if ($res) {
            return redirect()->route('employee.index')->with('success', 'Employee '.Config::get('constant.UPDATE_MESSAGE'));
        } else {
            return redirect()->route('employee.index')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
        }
    }
    
    
    public function get_company_employee(Request $request) {
        $employee = new User;
        $res = $employee->select('*',DB::raw('CASE WHEN is_active = "1" THEN "Yes" ELSE "No" END AS active,CASE WHEN is_suspended = "1" THEN "Yes" ELSE "No" END AS suspended'))
                ->whereIn('role_id',[2,3])->where('id', '!=' , Auth::user()->id)->where('company_id',Auth::user()->company_id)->whereNULL('deleted_at');
        return Datatables::of($res)->filter(function ($query) use ($request) {
                if ($request->has('employee_name')) {
                    $query->where('name', 'like', "%{$request->get('employee_name')}%");
                }
                if ($request->has('employee_email')) {
                    $query->where('email', 'like', "%{$request->get('employee_email')}%");
                }
        })->addColumn('actions' , function ( $row )
        {
            return '<a href="'.route('employee.edit' , [ $row->id ]).'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
        })->rawColumns(['actions'])->make(true);
    }
}
?>
