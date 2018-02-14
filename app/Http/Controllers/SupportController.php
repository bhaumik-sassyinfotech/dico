<?php

namespace App\Http\Controllers;

use App\Support;
use App\EmailTemplate;
use Config;
use Helpers;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;

class SupportController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $supportList = Support::get();
        return view('support.index', compact('supportList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('support.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'issue' => 'required',
            'description' => 'required'
        ]);
        $support = new Support;
        $support->issue = $request->input('issue');
        $support->description = $request->input('description');
        if ($support->save()) {
            $emailTemplate = EmailTemplate::where('slug', 'support')->first();

            if ($emailTemplate != null) {
                $parse = [
                    'ISSUE' => $request->input('issue'),
                    'DESC' => $request->input('description')
                ];

                $emailTemplate->email_body = Helpers::parseTemplate($emailTemplate->email_body, $parse);
                $emailTemplate->email_body = html_entity_decode($emailTemplate->email_body);
                $emailTemplate->email = Helpers::getSettings('app_features')->email1;
                $post = array('emailTemplate' => $emailTemplate, 'request' => $request, 'message' => $emailTemplate->email_body, 'email' => $emailTemplate->email);
//dd($post);
                Helpers::sendEmail($post);
            }
            return redirect()->route('support.create')->with('success', 'Support ' . Config::get('constant.ADDED_MESSAGE'));
        } else {
            return redirect()->route('support.create')->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
        }
    }

    public function deleteSupport()
    {
        if (isset($_POST['support_id']) && $_POST['support_id'] == '') {
            return json_encode(array('err_msg' => 'Feedback id  is required.', 'support_id' => $_POST['support_id']));
        }
        $id = $_POST['support_id'];
        $support = Support::findOrFail($id);
        $support->delete();
        return json_encode(array('msg' => 'Support has been deleted successfully', 'support_id' => $_POST['support_id']));
    }
    
    public function supportList(Request $request) {
        $support = new Support;
        $res = $support->select('*');
        return Datatables::of($res)->addColumn('actions', function ( $row ) {
                return '<a  href="javascript:void(0)"  class="label label-danger" onclick="deleteSupport(' . $row->id . ')"><i class="fa fa-trash-o"></i></a>';
                })->rawColumns(['actions', 'description'])->make(true);
    }
}
