<?php

namespace App\Http\Controllers;

use Helpers;
use Illuminate\Http\Request;
use App\Faqs;
use DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Redirect;
use Config;
use Carbon;
use Yajra\Datatables\Datatables;

class FaqsController extends Controller {

    public function __construct(Request $request) {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role_id != 1) {
                return redirect('/index');
            }
            return $next($request);
        });
    }

    /**
     * Use the following function to get faqs list design
     */
    public function index() {
        //dd("here");
        return view('superadmin.faqs.index');
    }

    /**
     * Use the following function to get faqs list from faqs table
     */
    public function create() {
        return view('superadmin.faqs.create');
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                        'question' => 'required',
                        'answer' => 'required'
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }

            $faqs = new Faqs;
            $faqs->question = $request->get('question', null);
            $faqs->answer = $request->get('answer', null);

            if ($faqs->save()) {
                return Redirect::route('adminfaq.index')->with('success', __('label.FAQs') .' '.  __('label.ADDED_MESSAGE'));
            } else {
                return Redirect::route('adminfaq.index')->with('error', '' . Config::get('constant.TRY_MESSAGE'));
            }
        } catch (\exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id) {
        $id = Helpers::decode_url($id);
        $faqs = Faqs::findOrFail($id);
        return view('superadmin.faqs.edit', compact('faqs'));
    }

    public function update(Request $request, $id) {
        try {

            $validator = Validator::make($request->all(), [
                        'question' => 'required',
                        'answer' => 'required'
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }

            $faqs = Faqs::findOrFail($id);
            $faqs->question = $request->get('question', null);
            $faqs->answer = $request->get('answer', null);

            if ($faqs->save()) {
                return Redirect::route('adminfaq.index')->with('success', __('label.FAQs') .' '.  __('label.UPDATE_MESSAGE'));
            } else {
                return Redirect::route('adminfaq.index')->with('error', '' . __('label.TRY_MESSAGE'));
            }
        } catch (\exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function faqList(Request $request) {
        $faqs = new Faqs;
        $res = $faqs->select('*')
                        ->whereNULL('deleted_at')->orderBy('id', 'desc');
        return Datatables::of($res)->addColumn('actions', function ( $row ) {
                    return '<a href="' . route('adminfaq.edit', [Helpers::encode_url($row->id)]) . '" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;<a  href="javascript:void(0)"  class="label label-danger" onclick="deleteFaqs(' . $row->id . ')"><i class="fa fa-trash-o"></i></a>';
                })->rawColumns(['actions', 'answer'])->make(true);
    }
    
     public function deleteFaqs(Request $request) {
        if (isset($_POST['faqs_id']) && $_POST['faqs_id'] == '') {
            return json_encode(array('err_msg' => __('label.Faqs id is required'), 'faqs_id' => $_POST['faqs_id']));
        }
        $id = $_POST['faqs_id'];
        $faqs = Faqs::findOrFail($id);
        $faqs->delete();
        return json_encode(array('msg' => __('label.Faqs has been deleted successfully'), 'faqs_id' => $_POST['faqs_id']));
    }

}

?>
