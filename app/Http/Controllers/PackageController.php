<?php

namespace App\Http\Controllers;

use Helpers;
use Illuminate\Http\Request;
use App\Packages;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Redirect;
use Config;
use Carbon;
use Yajra\Datatables\Datatables;

class PackageController extends Controller {

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
        return view('superadmin.packages.index');
    }

    /**
     * Use the following function to get packages list from packages table
     */
    public function create() {
        
    }

    public function edit($id) {
        $id = Helpers::decode_url($id);
        $packages = Packages::findOrFail($id);
        return view('superadmin.packages.edit', compact('packages'));
    }

    public function update(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                        'name' => 'required',
                        'amount' => 'required',
                        'total_user' => 'required'
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
           
            $packages = Packages::findOrFail($id);
            $packages->name = $request->get('name', null);
            $packages->amount = $request->get('amount', null);
            $packages->total_user = $request->get('total_user', null);
            
            if ($packages->save()) {
                return Redirect::route('packages.index')->with('success', __('label.adPackage'). __('label.UPDATE_MESSAGE'));
            } else {
                return Redirect::route('packages.index')->with('error', '' . __('label.TRY_MESSAGE'));
            }
        } catch (\exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function packagesList(Request $request) {
        $packages = new Packages;
        $res = $packages->select('*')
                        ->whereNULL('deleted_at');
        return Datatables::of($res)->addColumn('actions', function ( $row ) {
                    return '<a href="' . route('packages.edit', [Helpers::encode_url($row->id)]) . '" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                })->rawColumns(['actions','description'])->make(true);
    }

}

?>
