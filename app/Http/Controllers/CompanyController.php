<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use DB;
use Validator;
use Redirect;
use Config;
use Carbon;
use Yajra\Datatables\Datatables;


class CompanyController extends Controller {
    
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
        return view('company.index');
    }
    
    public function create() {
        return view('company.create');
    }

    public function store(Request $request) {
         
        $this->validate($request, [
            'company_name' => 'required|max:255|unique:companies,company_name'
        ]);
        if($request->input('allow_anonymous')) {
            $allow_anonymous = 1;
        } else {
            $allow_anonymous = 0;
        }
        if($request->input('allow_add_admin')) {
            $allow_add_admin = 1;
        } else {
            $allow_add_admin = 0;
        }

        $company = new Company;
        $company->company_name = $request->input('company_name');
        $company->description = $request->input('company_description');
        $company->allow_anonymous = $allow_anonymous;
        $company->allow_add_admin = $allow_add_admin;
        $company->company_admin = 1;//$request->input('company_admin');
        $company->created_at = Carbon\Carbon::now();
        if ($company->save()) {
            return redirect()->route('company.index')->with('success', 'Company '.Config::get('constant.ADDED_MESSAGE'));
        } else {
           return redirect()->route('company.index')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
        }
    }
    
    public function edit($id) {
        $obj = new Company;
        $company = $obj->where('id',$id)->first();
        return view('company.edit', compact('company'));
    }
    
    public function update(Request $request, $id) {
        $this->validate($request, [
            'company_name' => 'required|max:255|unique:companies,company_name,'.$id
        ]);
        $company = new Company;
        if($request->input('allow_anonymous')) {
            $allow_anonymous = 1;
        } else {
            $allow_anonymous = 0;
        }
        if($request->input('allow_add_admin')) {
            $allow_add_admin = 1;
        } else {
            $allow_add_admin = 0;
        }
        
        $postData = array('company_name'=>$request->input('company_name'),'description'=>$request->input('company_description'),'allow_anonymous'=>$allow_anonymous,'allow_add_admin'=>$allow_add_admin,'company_admin'=>1,'updated_at'=>Carbon\Carbon::now());
        $res = $company->where('id', $id)->update($postData);
        if ($res) {
            return redirect()->route('company.index')->with('success', 'Company '.Config::get('constant.UPDATE_MESSAGE'));
        } else {
            return redirect()->route('company.index')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
        }
    }
    
    
    public function get_company() {
        $company = new Company;
        $res = $company->select('*',DB::raw('CASE WHEN allow_anonymous = "1" THEN "Yes" ELSE "No" END AS anonymous,CASE WHEN allow_add_admin = "1" THEN "Yes" ELSE "No" END AS add_admin'))
                ->whereNULL('deleted_at');
                return Datatables::of($res)->addColumn('actions' , function ( $row )
                {
                    return '<a href="'.route('company.edit' , [ $row->id ]).'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                })->rawColumns(['actions'])->make(true);
    }
}
?>
