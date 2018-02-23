<?php

namespace App\Http\Controllers;

use Helpers;
use Illuminate\Http\Request;
use App\Contactus;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Redirect;
use Config;
use Carbon;
use Yajra\Datatables\Datatables;

class ContactusController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //protected $table = 'room';
    public function __construct(Request $request) {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role_id != 1) {
                return redirect('/index');
            }
            return $next($request);
        });
    }

    /**
     * Use the following function to get packages list design
     */
    public function index() {
        //dd("here");
        return view('superadmin.contactUs.index');
    }

    /**
     * Use the following function to get contactUs list from contactUs table
     */
    public function create() {
        
    }

   
    public function contactUsList(Request $request) {
        $contactUs = new Contactus;
        $res = $contactUs->select('*');                        
        return Datatables::of($res)->addColumn('actions', function ( $row ) {
                    return '<a  href="javascript:void(0)"  class="label label-danger" onclick="contactDelete(' . $row->id . ')"><i class="fa fa-trash-o"></i></a>';
                })->rawColumns(['actions','message'])->make(true);
    }
    public function contactDelete(Request $request) {
        
        if (isset($_POST['contact_id']) && $_POST['contact_id'] == '') {
            return json_encode(array('err_msg' => __('label.Contact is required'), 'contact_id' => $_POST['contact_id']));
        }
        $id = $_POST['contact_id'];
        $contactUs = Contactus::findOrFail($id);
        $contactUs->delete();
        return json_encode(array('msg' => __('label.Contact has been deleted successfully'), 'contact_id' => $_POST['contact_id']));
    }
}

?>
