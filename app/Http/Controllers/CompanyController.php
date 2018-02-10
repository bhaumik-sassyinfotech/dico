<?php
namespace App\Http\Controllers;

use Helpers;
use Illuminate\Http\Request;
use App\Company;
use App\Point;
use App\CompanyPoint;
use DB;
use Validator;
use Redirect;
use Config;
use Carbon;
use Yajra\Datatables\Datatables;
use Auth;


class CompanyController extends Controller {
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //protected $table = 'room';
    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            if(Auth::user()->role_id != 1) {
               return redirect('/index');
            }
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd("here");
        return view('superadmin.company.index');
    }
    
    public function create() {
        return view('superadmin.company.create');
    }

    public function store(Request $request) {
        //dd($request->all());
        try {
            $validator = Validator::make($request->all(),
            [
                'company_name' => 'required|max:255|unique:companies,company_name',
                'file_upload' => 'required'
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
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
            DB::beginTransaction();
            $company = new Company;
            $company->company_name = $request->input('company_name');
            $company->description = $request->input('company_description');
            $company->allow_anonymous = $allow_anonymous;
            $company->allow_add_admin = $allow_add_admin;
            $company->company_admin = 1;//$request->input('company_admin');
            $company->created_at = Carbon\Carbon::now();
            $file = $request->file('file_upload');
            if ($file != "")
            {
                $postData = array();
                //echo "here";die();
                $fileName        = $file->getClientOriginalName();
                $extension       = $file->getClientOriginalExtension();
                $folderName      = '/uploads/company_logo/';
                $destinationPath = public_path() . $folderName;
                $safeName        = str_random(10) . '.' . $extension;
                $file->move($destinationPath , $safeName);
                //$attachment = new Attachment;
                $company->company_logo = $safeName;
            }
            $company->save();
            $points = Point::select('activity','points','notes',DB::raw($company->id.' as company_id'))->whereNull('deleted_at')->get()->toArray();
            $company_points = CompanyPoint::insert($points);
            DB::commit();
            if ($company_points) {
                return redirect()->route('company.index')->with('success', 'Company '.Config::get('constant.ADDED_MESSAGE'));
            } else {
               return redirect()->route('company.index')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
            }
        }catch (\exception $e) {
            DB::rollback();
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }
    
    public function edit($id) {
        $id = Helpers::decode_url($id);
        $obj = new Company;
        $company = $obj->where('id',$id)->orderBy('id','desc')->first();
        return view('superadmin.company.edit', compact('company'));
    }
    
    public function update(Request $request, $id) {
        try{
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
            $file = $request->file('file_upload');
            if ($file != "")
            {
                $postData = array();
                //echo "here";die();
                $fileName        = $file->getClientOriginalName();
                $extension       = $file->getClientOriginalExtension();
                $folderName      = '/uploads/company_logo/';
                $destinationPath = public_path() . $folderName;
                $safeName        = str_random(10) . '.' . $extension;
                $file->move($destinationPath , $safeName);
                //$attachment = new Attachment;
                $postData['company_logo'] = $safeName;
            }
            $res = $company->where('id', $id)->update($postData);
            if ($res) {
                if ($file != "") {
                    if(file_exists(UPLOAD_PATH.$request->company_logo)) {
                        unlink(UPLOAD_PATH.$request->company_logo);
                    }
                }
                return redirect()->route('company.index')->with('success', 'Company '.Config::get('constant.UPDATE_MESSAGE'));
            } else {
                return redirect()->route('company.index')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
            }
        }catch (\exception $e) {
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }
    
    
    public function get_company(Request $request) {
        $company = new Company;
        $res = $company->select('*',DB::raw('CASE WHEN allow_anonymous = "1" THEN "Yes" ELSE "No" END AS anonymous,CASE WHEN allow_add_admin = "1" THEN "Yes" ELSE "No" END AS add_admin'))
                ->whereNULL('deleted_at');//->orderBy('id','desc');
                return Datatables::of($res)->filter(function ($query) use ($request) {
                if ($request->has('company_name')) {
                    $query->where('company_name', 'like', "%{$request->get('company_name')}%");
                }
                })->addColumn('actions' , function ( $row )
                {
                    return '<a href="'.route('company.edit' , [ Helpers::encode_url($row->id) ]).'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                })->rawColumns(['actions'])->make(true);
    }
}
?>
