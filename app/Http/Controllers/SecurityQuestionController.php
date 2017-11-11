<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SecurityQuestion;
use DB;
use Validator;
use Redirect;
use Config;
use Carbon;
use Yajra\Datatables\Datatables;


class SecurityQuestionController extends Controller {
    
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
        return view('security_question.index');
    }
    
    public function create() {
        return view('security_question.create');
    }

    public function store(Request $request) {
         
        $this->validate($request, [
            'question' => 'required|unique:security_questions,question'
        ]);
        
        $security_question = new SecurityQuestion;
        $security_question->question = $request->input('question');
        $security_question->created_at = Carbon\Carbon::now();
        if ($security_question->save()) {
            return redirect()->route('security_question.index')->with('success', 'Security Question '.Config::get('constant.ADDED_MESSAGE'));
        } else {
           return redirect()->route('security_question.index')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
        }
    }
    
    public function edit($id) {
        $obj = new SecurityQuestion;
        $security_question = $obj->where('id',$id)->first();
        return view('security_question.edit', compact('security_question'));
    }
    
    public function update(Request $request, $id) {
        $this->validate($request, [
            'question' => 'required|unique:security_questions,question,'.$id
        ]);
        $security_question = new SecurityQuestion;
        
        $postData = array('question'=>$request->input('question'),'updated_at'=>Carbon\Carbon::now());
        $res = $security_question->where('id', $id)->update($postData);
        if ($res) {
            return redirect()->route('security_question.index')->with('success', 'Security Question '.Config::get('constant.UPDATE_MESSAGE'));
        } else {
            return redirect()->route('security_question.index')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
        }
    }
    
    
    public function get_security_question(Request $request) {
        $security_question = new SecurityQuestion;
        $res = $security_question->select('*')
                ->whereNULL('deleted_at');
                return Datatables::of($res)->addColumn('actions' , function ( $row )
                {
                    return '<a href="'.route('security_question.edit' , [ $row->id ]).'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                })->rawColumns(['actions'])->make(true);
    }
}
?>
