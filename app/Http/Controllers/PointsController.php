<?php
namespace App\Http\Controllers;

use Helpers;
    use Illuminate\Http\Request;
use App\Point;
use App\CompanyPoint;
use DB;
use Validator;
use Redirect;
use Config;
use Carbon;
use Yajra\Datatables\Datatables;
use Auth;


class PointsController extends Controller {
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //protected $table = 'room';
    public $folder;
    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            if(Auth::user()->role_id == 3) {
               return redirect('/index');
            }
            if(Auth::user()->role_id == 1) {
                $this->folder = 'superadmin';
            }else if(Auth::user()->role_id == 2) {
                $this->folder = 'companyadmin';
            }else if(Auth::user()->role_id == 3) {
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
    public function index()
    {
        //dd("here");
        return view($this->folder.'.points.index');
    }
    
    public function create() {
        return view($this->folder.'.points.create');
    }

    public function store(Request $request) {
        try {
            if(Auth::user()) {
                $this->validate($request, [
                    'activity' => 'required|max:255|unique:point_master,activity',
                    'points' => 'required|numeric'
                ]);

                if(Auth::user()->role_id == 1) {
                    $points = new Point;
                } else {
                    $points = new CompanyPoint;
                    $points->company_id = Auth::user()->company_id;
                }
                $points->activity = $request->input('activity');
                $points->points = $request->input('points');
                $points->notes = $request->input('notes');
                $points->created_at = Carbon\Carbon::now();
                if ($points->save()) {
                    return redirect()->route('points.index')->with('success', 'Points '.Config::get('constant.ADDED_MESSAGE'));
                } else {
                   return redirect()->route('points.index')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
                }
            }
            else {
                return redirect('/index');
            }
        }catch (\exception $e) {
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }
    
    public function edit($id) {
       // $obj = new Company;
        $id = Helpers::decode_url($id);
        if(Auth::user()) {
            if(Auth::user()->role_id == 1) {
                $points = new Point;
            } else {
                $points = new CompanyPoint;
            }
            $point = $points->where('id',$id)->first();
            return view($this->folder.'.points.edit', compact('point'));
        } else {
            return redirect('/index');
        }
    }
    
    public function update(Request $request, $id) {
        try {
            if(Auth::user()) {
                $this->validate($request, [
                    'activity' => 'required|max:255|unique:point_master,activity,'.$id,
                    'points' => 'required|numeric'
                ]);
                $where = array('id'=>$id);
                if(Auth::user()->role_id == 1) {
                    $point = new Point;
                } else {
                    $point = new CompanyPoint;
                    $where['company_id'] = Auth::user()->company_id;
                }
                $postData = array('activity'=>$request->input('activity'),'points'=>$request->input('points'),'notes'=>$request->input('notes'),'updated_at'=>Carbon\Carbon::now());

                $res = $point->where($where)->update($postData);
                if ($res) {
                    return redirect()->route('points.index')->with('success', 'Points '.Config::get('constant.UPDATE_MESSAGE'));
                } else {
                    return redirect()->route('points.index')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
                }
            } else {
                return redirect('/index');
            }
        }catch (\exception $e) {
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }
    
    
    public function get_points(Request $request) {
        //$company = new Company;
        //$res = $company->select('*',DB::raw('CASE WHEN allow_anonymous = "1" THEN "Yes" ELSE "No" END AS anonymous,CASE WHEN allow_add_admin = "1" THEN "Yes" ELSE "No" END AS add_admin'))
          //      ->whereNULL('deleted_at');
        if(Auth::user()) {
            if(Auth::user()->role_id == 1) {
                $points = new Point;
                $res = $points->select('*')->whereNULL('deleted_at');
            } else if(Auth::user()->role_id == 2) {
                $points = new CompanyPoint;
                $res = $points->select('*')->where('company_id',Auth::user()->company_id)->whereNULL('deleted_at');
            }
            return Datatables::of($res)->filter(function ($query) use ($request) {
            if ($request->has('activity')) {
                $query->where('activity', 'like', "%{$request->get('activity')}%");
            }
            })->addColumn('actions' , function ( $row )
            {
                return '<a href="'.route('points.edit' , [ Helpers::encode_url($row->id) ]).'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
            })->rawColumns(['actions'])->make(true);
        } else {
            return redirect('/index');
        }
    }
}
?>
