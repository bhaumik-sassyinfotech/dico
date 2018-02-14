<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\EmailTemplate;
use Config;
use Helpers;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role_id != 1) {
              return redirect('/index');
          }
          //$feedbackList = Feedback::get();
          return view('feedback.index');//, compact('feedbackList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('feedback.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $this->validate($request, [
            'subject' => 'required',
            'description' => 'required'            
        ]);
        $feedback = new Feedback;
        $feedback->subject = $request->input('subject');
        $feedback->description = $request->input('description');      
        if ($feedback->save()) {
             $emailTemplate = EmailTemplate::where('slug', 'feedback')->first();

            if ($emailTemplate != null) {
                $parse = [
                    'SUBJECT' => $request->input('subject'),
                    'DESC' => $request->input('description')
                ];

                $emailTemplate->email_body = Helpers::parseTemplate($emailTemplate->email_body, $parse);
                $emailTemplate->email_body = html_entity_decode($emailTemplate->email_body);
                $emailTemplate->email = Helpers::getSettings('app_features')->email1;
                $post = array('emailTemplate' => $emailTemplate, 'request' => $request, 'message' => $emailTemplate->email_body, 'email' => $emailTemplate->email);
//dd($post);
                Helpers::sendEmail($post);
            }
            return redirect()->route('feedback.create')->with('success', 'Feedback '.Config::get('constant.ADDED_MESSAGE'));
        } else {
           return redirect()->route('feedback.create')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteFeedback()
    {
        if (isset($_POST['feed_id']) && $_POST['feed_id'] == '') {
            return json_encode(array('err_msg' => 'Feedback id  is required.', 'feed_id' => $_POST['feed_id']));
        }
        $id = $_POST['feed_id'];
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        return json_encode(array('msg' => 'Feedback has been deleted successfully', 'feed_id' => $_POST['feed_id']));
    }
    
    public function feedbackList(Request $request) {
        $feedback = new Feedback;
        $res = $feedback->select('*');
        return Datatables::of($res)->addColumn('actions', function ( $row ) {
                return '<a  href="javascript:void(0)"  class="label label-danger" onclick="deleteFeedback(' . $row->id . ')"><i class="fa fa-trash-o"></i></a>';
                })->rawColumns(['actions', 'description'])->make(true);
    }
}
