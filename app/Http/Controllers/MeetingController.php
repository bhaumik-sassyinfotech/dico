<?php
    
    namespace App\Http\Controllers;
    
    use App\Attachment;
    use App\Company;
    use App\Group;
    use App\GroupUser;
    use App\Meeting;
    use App\MeetingCommentReply;
    use App\MeetingUser;
    use App\MeetingComment;
    use App\MeetingCommentLikes;
    use App\MeetingAttachment;
    use App\User;
    use Carbon\Carbon;
    use Config;
    use DB;
    use Exception;
    use Helpers;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Redirect;
    use Response;
    use Symfony\Component\VarDumper\Cloner\Data;
    use Validator;
    use Yajra\DataTables\DataTables;
    use View;
    
    class MeetingController extends Controller
    {
        protected $folder;
        
        public function __construct()
        {
//            echo "meeting controller cunsructor call";
            $this->middleware(function ( $request, $next ) {
                if ( Auth::user()->role_id == 1 ) {
                    return Redirect::to(url('/'));
                    $this->folder = 'superadmin';
                } else if ( Auth::user()->role_id == 2 ) {
                    $this->folder = 'companyadmin';
                } else if ( Auth::user()->role_id == 3 ) {
                    $this->folder = 'employee';
                }
                
                return $next($request);
            });
        }
        
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            $currUser = Auth::user();
            $user_id  = $currUser->id;
            
            /*fetch list of meeting's id which current user is part of*/
            $user_meetings = MeetingUser::where('user_id', $user_id)->pluck('meeting_id')->toArray();
            
            /*
             * Private meetings will only be included in listing if user is part of it.
             * So select meeting's id user is currently in and then check against the primary_key in `meetings` table
             *
             * */
            $allMeetingsQuery = Meeting::withCount('meetingUsers')->has('meetingUsers.UserDetail')->orderByDesc('id');
            $count_allMeetings = count($allMeetingsQuery->get());
            $allMeetings = $allMeetingsQuery->limit(POST_DISPLAY_LIMIT)->get();
            $myMeetingsQuery = Meeting::withCount('meetingUsers')->whereIn('id', $user_meetings)->has('meetingUsers.UserDetail')->orderByDesc('id');
            $count_myMeetings = count($myMeetingsQuery->get());
            $myMeetings = $myMeetingsQuery->limit(POST_DISPLAY_LIMIT)->get();
//    dd($meetings);
            return view($this->folder . '.meeting.index' , compact('myMeetings','allMeetings','count_allMeetings','count_myMeetings'));
        }
        
        
        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            
            $currUser   = Auth::user();
            $company_id = $currUser->company_id;
            $employees  = User::where('company_id', $company_id)->where('role_id', '!=', 1)->where('id', '!=', $currUser->id)->orderByDesc('id')->get();
            $groups     = Group::where('company_id', $company_id)->orderByDesc('id')->get();
            
            return view($this->folder . '.meeting.create', compact('employees', 'groups', 'company_id'));
        }
        
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response
         */
        public function store( Request $request )
        {
            //echo "<pre>";print_r($_POST);die;
            DB::beginTransaction();
            try {
                $currUser   = Auth::user();
                $validator = Validator::make($request->all(),
                [
                        'privacy' => 'required',
                        'meeting_title' => 'required|max:' . POST_TITLE_LIMIT,
                ]);
                if ($validator->fails()) {
                        return Redirect::back()->withErrors($validator)->withInput();
                }
                $company_id = $request->company_id;
                $privacy    = ($request->privacy == 'public') ? 0 : 1;
                if(!empty($request->group)) {
                    $meeting_group = implode(",", $request->group);
                } else {
                    $meeting_group = NULL;
                }
                $meeting                      = new Meeting;
                $meeting->meeting_title       = $request->meeting_title;
                $meeting->meeting_description = $request->meeting_description;
                $meeting->date_of_meeting     = date("Y-m-d H:i",strtotime($request->date_of_meet));
                $meeting->privacy             = $privacy;
                $meeting->created_by          = $currUser->id;
                $meeting->group_id            = $meeting_group;
                //dd($meeting);
                if ( $meeting->save() ) {
//                    dd("abc");
                    $file = $request->file('file_upload');
                    //dd($file);
                    if ($file != "") {
                            $postData = array();
                            //echo "here";die();
                            $fileName = $file->getClientOriginalName();
                            $extension = $file->getClientOriginalExtension();
                            $folderName = '/uploads/';
                            $destinationPath = public_path() . $folderName;
                            $safeName = str_random(10) . '.' . $extension;
                            $file->move($destinationPath, $safeName);
                            $attachment = new MeetingAttachment;
                            $attachment->file_name = $safeName;
                            $attachment->type = 1;
                            $attachment->type_id = $meeting->id;
                            $attachment->user_id = Auth::user()->id;
                            //dd($attachment);
                            $attachment->save();
                            // $attachment = Attachment::insert($postData);
                    }
                    $group        = $users = [];
                    $meeting_id   = $meeting->id;
                    $meetingUsers = [ ['meeting_id' => $meeting->id , 'user_id' => $currUser->id, 'is_admin' => 1, 'group_id' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ] ];
                    $selected_members = explode(",",$request->selected_members);
                    if ( !empty($selected_members) )
                    {
                        foreach ( $selected_members as $emp )
                        {
                            $users[] = $emp;
                        }
                    }
                    if ( !empty($request->group) ) {
                        foreach ( $request->group as $grp ) {
                            $group[] = $grp;
                        }
                    }
//                    dd([$users , $group]);
                    if ( !empty($group) )
                    {
                        foreach ( $group as $key => $val )
                        {
                            $now         = Carbon::now();
                            $group_id    = $val;
                            $group_users = GroupUser::where('group_id', $group_id)->where('company_id', $company_id)->pluck('user_id')->toArray();
                            
                            foreach ( $group_users as $k => $v )
                            {
                                $is_admin = 0;
                                if ( $v != $currUser->id )
                                {
                                    $meetingUsers[] = [ 'meeting_id' => $meeting->id ,'user_id' => $v, 'is_admin' => $is_admin, 'group_id' => $group_id, 'created_at' => $now, 'updated_at' => $now ];
                                }
                            }
                        }
                    }
                    
                    if ( !empty($users) )
                    {
                        $now = Carbon::now();
                        foreach ( $users as $key => $val )
                        {
                            $is_admin = 0;
                            if ( $val != $currUser->id )
                            {
                                if(!in_array($val,array_pluck($meetingUsers,'user_id'))) {
                                    $meetingUsers[] = [ 'meeting_id' => $meeting->id , 'user_id' => $val, 'is_admin' => $is_admin, 'group_id' => $group_id, 'created_at' => $now, 'updated_at' => $now ];
                                }
                            }
                        }
                    }
                    //echo "<pre>";print_r($meetingUsers);die;
                    //dd($meetingUsers);
                    //return $meetingUsers;
                    
                    if ( MeetingUser::insert($meetingUsers) ) {
                        DB::commit();
                        echo json_encode(array('status' => 1, 'msg' => 'Meeting has been created successfully.'));
                        //return redirect()->route('meeting.index')->with('success', 'Meeting has been created successfully.');
                    } else {
                        DB::rollBack();
                    }
                } else {
                    DB::rollBack();
                    echo json_encode(array('status' => 0, 'msg' => Config::get('constant.TRY_MESSAGE')));
                    //return redirect()->back()->with('err_msg', 'Some error occurred.')->withInput();
                }
            }
            catch ( Exception $ex ) {
                
                DB::rollBack();
                echo json_encode(array('status' => 0, 'msg' => $ex->getMessage()));
                //return redirect()->back()->with('err_msg', $ex->getMessage())->withInput();
            }
            
            DB::rollBack();
        }
        
        /**
         * Display the specified resource.
         *
         * @param  int    $id
         *
         * @param Request $request
         *
         * @return \Illuminate\Http\Response
         */
        public function show( $id, Request $request )
        {
            //
            $id      = Helpers::decode_url($id);
            if(is_numeric($id)) {
            $meeting = Meeting::with(['meetingCreator' , 'meetingUser', 'meetingUser.following', 'meetingAttachment', 'meetingAttachment.attachmentUser', 'meetingComment' => function ($q) {
                $q->take(COMMENT_DISPLAY_LIMIT);
            }, 'meetingComment.commentUser', 'meetingComment.commentAttachment', 'meetingComment.commentReply', 'meetingComment.commentReply.commentReplyUser','meetingComment.commentLike','meetingComment.commentUserLike','meetingComment.commentDisLike','meetingComment.commentUserDisLike' ])->withCount('meetingComment')->where('id', $id)->first();
            if($meeting) {
                $type_ids = MeetingComment::where('meeting_id',$meeting->id)->pluck('id')->toArray();
                $uploadedFiles = MeetingAttachment::with('attachmentUser')->whereIn('type_id',$type_ids)->orderBy('created_at','ASC')->get();
                $meeting_user_ids = array_values(array_unique(MeetingUser::where('meeting_id', $meeting->id)->pluck('user_id')->toArray()));
                $meeting_users    = User::whereIn('id', $meeting_user_ids)->get();
            
                if($meeting->privacy == '1')
                {
                    if( !in_array( Auth::user()->id , $meeting_user_ids ) )
                        return Redirect::route('meeting.index')->with('err_msg' , 'You are not allowed to access a private meeting as you not a part of it.');
                }
                //dd($meeting);
    //            return $uploadedFiles = MeetingAttachment::with(['attachmentUser'])->whereIn('user_id',$meeting_user_ids)->get();
    //             $comments = Meeting::with()->where('id', $id)->get();

    //                ->select('*',DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
    //            dd($meeting);
                return view($this->folder . '.meeting.detail', compact('meeting', 'meeting_users', 'meeting_user_ids' , 'comments','uploadedFiles'));
            }
            else {
                return redirect()->back()->with('err_msg', Config::get('constant.TRY_MESSAGE'))->withInput();
            }
            }else {
                return redirect()->back()->with('err_msg', Config::get('constant.TRY_MESSAGE'))->withInput();
            }
        }
        
        /**
         * Show the form for editing the specified resource.
         *
         * @param  int $id
         *
         * @return \Illuminate\Http\Response
         */
        public function edit( $id )
        {
            $id = Helpers::decode_url($id);
            $currUser = Auth::user();
            $meeting = Meeting::with(['meetingUser','meetingUsers.UserDetail','meetingAttachment'])->where('id',$id)->first();
            $company_id = $currUser->company_id;
            $employees  = User::where('company_id', $company_id)->where('role_id', '!=', 1)->where('id', '!=', $currUser->id)->orderByDesc('id')->get();
            $groups     = Group::where('company_id', $company_id)->orderByDesc('id')->get();
            if($currUser->id != $meeting->created_by)
            {
                return back();
            }
            if(!empty($meeting))
            {
                return view($this->folder.'.meeting.edit' , compact('meeting','employees', 'groups', 'company_id'));
            }
            
            return back();
        }
        
        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  int                      $id
         *
         * @return \Illuminate\Http\Response
         */
        public function update( Request $request, $id )
        {
//            dd($request->all());
            $currUser   = Auth::user();
            $id = Helpers::decode_url($id);
            $meeting = Meeting::find($id);
            $validator = Validator::make($request->all(),
            [
                    'privacy' => 'required',
                    'meeting_title' => 'required|max:' . POST_TITLE_LIMIT,
            ]);
            if ($validator->fails()) {
                    return Redirect::back()->withErrors($validator)->withInput();
            }
//            /echo "<pre>";print_r($request->all());die;
            $selected_members = explode(",",$request->selected_members);
            if ( !empty($selected_members) )
            {
                foreach ( $selected_members as $emp )
                {
                    $users[] = $emp;
                }
            }
            if(!empty($request->group)) {
                $meeting_group = implode(",", $request->group);
            } else {
                $meeting_group = NULL;
            }
            $company_id = $request->company_id;
            $privacy                      = ($request->privacy == 'public') ? 0 : 1;
            $meeting->meeting_title       = $request->input('meeting_title');
            $meeting->meeting_description = $request->input('meeting_description');
            $meeting->privacy             = $privacy;
            $meeting->group_id            = $meeting_group;
            
            DB::beginTransaction();
            if ( $meeting->save() )
            {
                $file = $request->file('file_upload');
                if ( $file != "" )
                {
                    $currUser = Auth::user();
                    $postData = array();
                    //echo "here";die();
                    $fileName        = $file->getClientOriginalName();
                    $extension       = $file->getClientOriginalExtension();
                    $folderName      = '/uploads/meeting/';
                    $destinationPath = public_path() . $folderName;
                    $safeName        = str_random(10) . '.' . $extension;
                    $file->move($destinationPath , $safeName);
                    //$attachment = new Attachment;
                    $postData[ 'file_name' ] = $safeName;
                    $postData[ 'original_file_name' ] = $fileName;
                    $postData[ 'type' ]      = 1;
                    $postData[ 'type_id' ]   = $id;
                    $postData[ 'user_id' ]   = $currUser->id;
//                        $attachment              = Attachment::insert($postData);
                    $attachment              = MeetingAttachment::where('type_id',$id)->where('type',1)->where('user_id',$currUser->id);
                    $now = Carbon::now();
                    if($attachment->first())
                    {
                        $attachment->update(['file_name' => $postData[ 'file_name' ] , 'updated_at' => $now , 'original_file_name' => $postData[ 'original_file_name' ]]);
                    } else {
                
                        $postData['created_at'] = $postData['updated_at'] = $now;
                        MeetingAttachment::insert($postData);
                    }
                }
                $meeting_user_remove = MeetingUser::where('meeting_id', $meeting->id)->forceDelete();
                $group        = $users = [];
                $meeting_id   = $meeting->id;
                $meetingUsers = [ ['meeting_id' => $meeting->id , 'user_id' => $currUser->id, 'is_admin' => 1, 'group_id' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ] ];
                $selected_members = explode(",",$request->selected_members);
                if ( !empty($selected_members) )
                {
                    foreach ( $selected_members as $emp )
                    {
                        $users[] = $emp;
                    }
                }
                if ( !empty($request->group) ) {
                    foreach ( $request->group as $grp ) {
                        $group[] = $grp;
                    }
                }
//                    dd([$users , $group]);
                if ( !empty($group) )
                {
                    foreach ( $group as $key => $val )
                    {
                        $now         = Carbon::now();
                        $group_id    = $val;
                        $group_users = GroupUser::where('group_id', $group_id)->where('company_id', $company_id)->pluck('user_id')->toArray();

                        foreach ( $group_users as $k => $v )
                        {
                            $is_admin = 0;
                            if ( $v != $currUser->id )
                            {
                                $meetingUsers[] = [ 'meeting_id' => $meeting->id ,'user_id' => $v, 'is_admin' => $is_admin, 'group_id' => $group_id, 'created_at' => $now, 'updated_at' => $now ];
                            }
                        }
                    }
                }
                if ( !empty($users) )
                {
                    $now = Carbon::now();
                    foreach ( $users as $key => $val )
                    {
                        $is_admin = 0;
                        if ( $val != $currUser->id )
                        {
                            if(!in_array($val,array_pluck($meetingUsers,'user_id'))) {
                                $meetingUsers[] = [ 'meeting_id' => $meeting->id , 'user_id' => $val, 'is_admin' => $is_admin, 'group_id' => $group_id, 'created_at' => $now, 'updated_at' => $now ];
                            }
                        }
                    }
                }
                    //echo "<pre>";print_r($meetingUsers);die;
                    //dd($meetingUsers);
                    //return $meetingUsers;
                    
                if ( MeetingUser::insert($meetingUsers) ) {
                    DB::commit();
                    return Redirect::route('meeting.index')->with('success','Meeting details has been saved successfully.');
                        //return redirect()->route('meeting.index')->with('success', 'Meeting has been created successfully.');
                    } else {
                        DB::rollBack();
                    }
            } else
            {
                DB::rollBack();
                return back()->withInput();
            }
        }
        
        /**
         * Remove the specified resource from storage.
         *
         * @param  int $id
         *
         * @return \Illuminate\Http\Response
         */
        public function destroy( $id )
        {
            //
        }
        
        public function meetingList( Request $request )
        {
            $currUser = Auth::user();
            $user_id  = $currUser->id;
            
            /*fetch list of meeting's id which current user is part of*/
            $user_meetings = MeetingUser::where('user_id', $user_id)->pluck('meeting_id')->toArray();
            
            /*
             * Private meetings will only be included in listing if user is part of it.
             * So select meeting's id user is currently in and then check against the primary_key in `meetings` table
             *
             * */
            DB::statement(DB::raw('set @rownum=0'));
            $meetings = Meeting::select([ DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'meetings.*' ])->withCount('meetingUsers')->where('privacy', 0)->orWhereIn('id', $user_meetings)->orderByDesc('id')->get();
            
            return DataTables::of($meetings)->addColumn('privacy', function ( $row ) {
                /*
                 * 0 => Public , 1 => Private
                 * */
                if ( $row->privacy == 0 )
                    return "Public";
                else
                    return "Private";
            })->addColumn('meeting_description', function ( $row ) {
                if ( empty($row->meeting_description) )
                    return "-";
                
                return $row->meeting_description;
            })->addColumn('actions', function ( $row ) {
//                $editBtn = '<a href="' . route('meeting.edit' , [ $row->id ]) . '" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                $showBtn   = '<a href="' . route('meeting.show', [ Helpers::encode_url($row->id) ]) . '" title="Show"><i class="fa fa-eye" aria-hidden="true"></i></a>';
//                $deleteBtn = '<a href="javascript:void(0);" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
//                $editBtn   = '<a href="' . route('meeting.edit', [ Helpers::encode_url($row->id) ]) . '" title="Show"><i class="fa fa-pencil" aria-hidden="true"></i></a>';

//                return $showBtn . " | " . $editBtn ." | " . $deleteBtn;
//                return $showBtn . " | " . $deleteBtn;
                return $showBtn;
            })->rawColumns([ 'actions' ])->make(TRUE);
        }
        
        public function saveComment( $id, Request $request )
        {
            try {
                if ( Auth::check() ) {
                    $user_id      = Auth::user()->id;
                    $comment_text = $request->input('comment_text');
                    
                    $validator    = $this->validation($request);
                    
                    if ($validator->fails())
                    {
                        return back()->withErrors($validator)->withInput();
                    }
                    DB::beginTransaction();
                    
                    $meetingComment                = new MeetingComment;
                    $meetingComment->user_id       = $user_id;
                    $meetingComment->meeting_id    = $id;
                    $meetingComment->comment_reply = $comment_text;
                    $res                          = 0;
                    if ( $meetingComment->save() ) {
                        
                        $res = $meetingComment->id;
                    } else {
                        
                        return back()->with('error', 'Some error occurred. Please try again later.');
                    }
                    $file = $request->file('file_upload');
                    
                    if ( $file != "" )
                    {
                        $fileName        = $file->getClientOriginalName();
                        $extension       = $file->getClientOriginalExtension();
                        $folderName      = '/uploads/meetings/';
                        $destinationPath = public_path() . $folderName;
                        $safeName        = str_random(10) . '.' . $extension;
                        
                        $file->move($destinationPath, $safeName);
                        
                        $attachment                     = new MeetingAttachment;
//                        $attachment->meeting_id         = $id;
                        $attachment->type               = 2;
                        $attachment->type_id            = $res;
                        $attachment->user_id            = Auth::user()->id;
                        $attachment->file_name          = $safeName;
                        $attachment->original_file_name = $fileName;
                        $attachment->save();
                        // $attachment = Attachment::insert($postData);
                    }
                    DB::commit();
                    if ( $res ) {
                        return Redirect::back()->with('success', 'Comment ' . Config::get('constant.ADDED_MESSAGE'));
                    } else {
                        return Redirect::back()->with('err_msg', 'Please try again.');
                    }
                } else {
                    return redirect('/index');
                }
            }
            catch ( \exception $e ) {
                dd($e);
                DB::rollback();
                
                return Redirect::back()->with('err_msg', $e->getMessage());
            }
        }
        
        public function deleteMeetingComment( Request $request )
        {
            $id = $request->input('comment_id');
            if(!empty($id))
            {
                $currUser= Auth::user();
                $response_data = [ 'status' => 0, 'msg' => 'Please try again later.', 'data' => [] ];
                $comment       = MeetingComment::find($id);
                if ( (!empty($comment)) && ($comment->user_id == $currUser->id))
                {
                    DB::beginTransaction();
                    if ( MeetingAttachment::where(array( 'type_id' => $id, 'type' => 2 ))->exists() )
                    {
                        MeetingAttachment::where(array( 'type_id' => $id, 'type' => 2 ))->delete();
                    }
                    $deleteComment = $comment->delete();
                    if ( $deleteComment )
                    {
                        DB::commit();
                        $response_data = ['status' => 1 , 'msg' => 'Comment has been deleted successfully.', 'data'=>[] ];
                    } else
                    {
                        DB::rollBack();
                    }
                }
            }
            return Response::json($response_data);
        }
        
        public function validation( Request $request )
        {
            
            $rulesArray = [];
            switch ( $request->method() )
            {
                case 'PUT':
                case 'PATCH':
                case 'GET':
                case 'DELETE':
                    $rulesArray = [];
                    break;
                case 'POST':
                    $rulesArray = [
                        'comment_text' => 'required',
                        'file_upload'  => 'sometimes|required|max:2048'
                    ];
                    break;
            }
            return  Validator::make($request->all(), $rulesArray);
        }
        
        public function deleteMeeting( $meeting_id )
        {
            $meeting_id = Helpers::decode_url($meeting_id);
            $meeting = Meeting::find($meeting_id);
            $currUser   = Auth::user();
//            if($meeting->created_by != $currUser->id)
//                return back();
            if(!empty($meeting))
            {
                MeetingAttachment::where('meeting_id', $meeting_id)->delete();
                $meeting_comment     = MeetingComment::where('meeting_id', $meeting_id)->get();
                
                if( count($meeting_comment) != 0 )
                {
                    $meeting_comment_ids = $meeting_comment->pluck('id');
                    MeetingCommentReply::whereIn('comment_id', $meeting_comment_ids)->delete();
                    $meeting_comment->delete();
                }
                MeetingUser::where('meeting_id', $meeting_id)->delete();
                if($meeting->delete())
                {
                    return Redirect::route('meeting.index')->with('success','Meeting has been deleted successfully.');
                }
                return Redirect::route('meeting.index')->with('error_msg','Please try again later.');
            }
            return Redirect::route('meeting.index')->with('error_msg','Please try again later.');
        }
        
        public function finalizeMeeting( Request $request )
        {
            $meeting_id = $request->input('meeting_id');
            $comment    = $request->input('meeting_comment');
            $summary    = $request->input('meeting_summary');
            $meeting = Meeting::where('id',$meeting_id)->first();
            
            if($meeting->created_by == Auth::user()->id)
            {
                Meeting::where('id', $meeting_id)->update([ 'meeting_comment' => $comment, 'meeting_summary' => $summary, 'is_finalized' => '1' ]);
                return back()->with('success',"Meeting has been finalized successfully.");
            }
            
            return back()->with('error',"Please try again later.");
        }
        
        public function updateComment( Request $request )
        {
            try
            {
                $this->validate($request, [
                    'comment' => 'required',
                ]);
                $comment_id   = $request->get('id');
                $comment_text = $request->get('comment');
                $res          = MeetingComment::where('id', $comment_id)->update([ 'comment_reply' => $comment_text ]);
                if ( $res )
                {
                    echo json_encode(array( 'status' => 1, 'msg' => 'Comment ' . Config::get('constant.UPDATE_MESSAGE') ));
                } else
                {
                    echo json_encode(array( 'status' => 0, 'msg' => Config::get('constant.TRY_MESSAGE') ));
                }
            }
            catch (Exception $ex) {
                echo json_encode(array('status' => 2,'msg' => $ex->getMessage()));
            }
        }
        
        public function replyToComment( Request $request )
        {
            $comment_id = $request->input('comment_id');
            $reply = $request->input('reply_text');
            $response_data = ['status' => 0 , 'msg' => 'Please try again later.' ,'data' => []];
            $currUser = Auth::user();
            if(!empty($comment_id) && !empty($reply))
            {
                $meeting_reply                = new MeetingCommentReply;
                $meeting_reply->comment_reply = $reply;
                $meeting_reply->user_id       = $currUser->id;
                $meeting_reply->comment_id    = $comment_id;
                if($meeting_reply->save())
                {
                    $replyCount    = MeetingCommentReply::where('comment_id', $comment_id)->get()->count();
                    $response_data = [ 'status' => 1, 'msg' => 'Reply has been saved successfully.', 'data' => [ 'count' => $replyCount ] ];
                }
            }
            return Response::json($response_data);
        }
    
        public function leaveMeeting( Request $request )
        {
            $currUser   = Auth::user();
            $meeting_id = $request->input('meeting_id');
            
            $response_data = ['status' => 0 , 'msg' => 'Please try again later.' ,'data' => []];
            if(Auth::check() && !empty($meeting_id))
            {
                DB::beginTransaction();
                try
                {
                    $meeting_users    = MeetingUser::where('meeting_id', $meeting_id)->where('user_id', $currUser->id);
                    $meeting_comments = MeetingComment::where('meeting_id', $meeting_id)->where('user_id', $currUser->id);
                    $comments_id      = $meeting_comments->get()->pluck('id')->toArray();
                    
                    MeetingCommentReply::whereIn('comment_id', $comments_id)->delete();
                    $meeting_comments->delete();
                    $delete = $meeting_users->delete();
                    DB::commit();
                    $response_data = [ 'status' => 1, 'msg' => 'You have successfully left the meeting.', 'data' => [] ];
                }
                catch (Exception $ex)
                {
                    DB::rollBack();
                    $response_data = [ 'status' => 0, 'msg' => 'Please try again later.', 'data' => $ex ];
                }
            }
            return Response::json($response_data);
        }
    
        public function deleteIdeaPost( $id, Request $request )
        {
            Post::where('id',$id)->delete();
            Attachment::where('post_id',$id)->delete();
        }
        
        //========================== Functions added by Ankita 22/01/2018 ===========================//
        public function uploadFileMeeting(Request $request) {
            try
            {
                if(Auth::user()) {
                    $id = $request->get('meeting_id');
                    $file = $request->file('file_upload');
                    if ($file != "")
                    {
                        $postData = array();
                        //echo "here";die();
                        $fileName        = $file->getClientOriginalName();
                        $extension       = $file->getClientOriginalExtension();
                        $folderName      = '/uploads/';
                        $destinationPath = public_path() . $folderName;
                        $safeName        = str_random(10) . '.' . $extension;
                        $file->move($destinationPath , $safeName);
                        //$attachment = new Attachment;
                        $postData[ 'file_name' ] = $safeName;
                        $postData[ 'type' ]      = 1;
                        $postData[ 'type_id' ]   = $id;
                        $postData[ 'user_id' ]   = Auth::user()->id;
                        $attachment              = MeetingAttachment::insert($postData);
                        if($attachment) {
                            $meeting = Meeting::with(['meetingAttachment','meetingAttachment.attachmentUser'])->where('id',$id)->first();
                            return view($this->folder . '.meeting.attachmentList',compact('meeting'));
                        }
                    }
                }else {
                    return redirect('/index')->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE')); 
                }
            }catch (Exception $ex) {
                echo json_encode(array('status' => 2,'msg' => $ex->getMessage()));
            }
        }
        public function meeting_comment_update(Request $request) {
            try
            {
                if(Auth::user()) {
                    $validator = Validator::make( $request->all(),
                    [
                        'id' => 'required',
                        'comment'  => 'required',
                    ]);
                    if ($validator->fails()) {
                        echo json_encode(array('status' => 0,'msg' => $validator->errors()->all()));
                    } else {
                        $comment_id = $request->get('id');
                        $comment_reply = $request->get('comment');
                        $res = MeetingComment::where('id',$comment_id)->update(['comment_reply'=>$comment_reply]);
                        if($res) {
                            echo json_encode(array('status' => 1,'msg' => 'Comment ' . Config::get('constant.UPDATE_MESSAGE')));
                        }else {
                            echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE')));
                        }
                    }
                } else {
                    return redirect('/index')->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE')); 
                }
            }
            catch (Exception $ex) {
                echo json_encode(array('status' => 2,'msg' => $ex->getMessage()));
            }
        }
        public function like_attachment_comment($id) {
            try
            {
                if ( Auth::user() )
                {
                    $f           = 0;
                    $user_id     = Auth::user()->id;
                    $commentlike = MeetingCommentLikes::where(array( 'user_id' => $user_id , 'meeting_comment_id' => $id ))->first();
                    if ( $commentlike )
                    {
                        if ( $commentlike->flag == 1 )
                        {
                            $deletelike     = $commentlike->forceDelete();
                            $likecomment    = MeetingCommentLikes::where(array( 'meeting_comment_id' => $id , 'flag' => 1 ))->get();
                            $dislikecomment = MeetingCommentLikes::where(array( 'meeting_comment_id' => $id , 'flag' => 2 ))->get();
                            if ( $deletelike )
                            {
                                echo json_encode(array( 'status' => 0 , 'msg' => "Remove comment Liked successfully" , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                            } else
                            {
                                echo json_encode(array( 'status' => 0 , 'msg' => Config::get('constant.TRY_MESSAGE') , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                            }
                        } else
                        {
                            $likecomment    = MeetingCommentLikes::where(array( 'user_id' => $user_id , 'meeting_comment_id' => $id ))->update(array( 'flag' => 1 ));
                            $likecomment    = MeetingCommentLikes::where(array( 'meeting_comment_id' => $id , 'flag' => 1 ))->get();
                            $dislikecomment = MeetingCommentLikes::where(array( 'meeting_comment_id' => $id , 'flag' => 2 ))->get();
                            if ( $likecomment )
                            {
                                echo json_encode(array( 'status' => 1 , 'msg' => "comment liked successfully" , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                            } else
                            {
                                echo json_encode(array( 'status' => 0 , 'msg' => Config::get('constant.TRY_MESSAGE') , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                            }
                        }
                        
                    } else
                    {
                        $likecomment    = MeetingCommentLikes::insert(array( 'user_id' => $user_id , 'meeting_comment_id' => $id , 'flag' => 1 ));
                        $likecomment    = MeetingCommentLikes::where(array( 'meeting_comment_id' => $id , 'flag' => 1 ))->get();
                        $dislikecomment = MeetingCommentLikes::where(array( 'meeting_comment_id' => $id , 'flag' => 2 ))->get();
                        if ( $likecomment )
                        {
                            echo json_encode(array( 'status' => 1 , 'msg' => "comment liked successfully" , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                        } else
                        {
                            echo json_encode(array( 'status' => 0 , 'msg' => Config::get('constant.TRY_MESSAGE') , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                        }
                    }
                    
                } else
                {
                    return redirect('/index');
                }
            }
            catch ( \exception $e )
            {
                echo json_encode(array( 'status' => 0 , 'msg' => $e->getMessage() ));
                //return Redirect::back()->with('err_msg', $e->getMessage());
            }
        }
        public function dislike_attachment_comment($id)
        {
            try
            {
                if ( Auth::user() )
                {
                    $user_id        = Auth::user()->id;
                    $commentdislike = MeetingCommentLikes::where(array( 'user_id' => $user_id , 'meeting_comment_id' => $id ))->first();
                    if ( $commentdislike )
                    {
                        if ( $commentdislike->flag == 2 )
                        {
                            $deletedislike  = $commentdislike->forceDelete();
                            $likecomment    = MeetingCommentLikes::where(array( 'meeting_comment_id' => $id , 'flag' => 1 ))->get();
                            $dislikecomment = MeetingCommentLikes::where(array( 'meeting_comment_id' => $id , 'flag' => 2 ))->get();
                            if ( $deletedislike )
                            {
                                echo json_encode(array( 'status' => 0 , 'msg' => "Remove comment disiked successfully" , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                            } else
                            {
                                echo json_encode(array( 'status' => 0 , 'msg' => Config::get('constant.TRY_MESSAGE') , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                            }
                        } else
                        {
                            $dislikecomment = MeetingCommentLikes::where(array( 'user_id' => $user_id , 'meeting_comment_id' => $id ))->update(array( 'flag' => 2 ));
                            $likecomment    = MeetingCommentLikes::where(array( 'meeting_comment_id' => $id , 'flag' => 1 ))->get();
                            $dislikecomment = MeetingCommentLikes::where(array( 'meeting_comment_id' => $id , 'flag' => 2 ))->get();
                            if ( $dislikecomment )
                            {
                                echo json_encode(array( 'status' => 1 , 'msg' => "comment disliked successfully" , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                            } else
                            {
                                echo json_encode(array( 'status' => 0 , 'msg' => Config::get('constant.TRY_MESSAGE') , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                            }
                        }
                        
                    } else
                    {
                        $dislikecomment = MeetingCommentLikes::insert(array( 'user_id' => $user_id , 'meeting_comment_id' => $id , 'flag' => 2 ));
                        $likecomment    = MeetingCommentLikes::where(array( 'meeting_comment_id' => $id , 'flag' => 1 ))->get();
                        $dislikecomment = MeetingCommentLikes::where(array( 'meeting_comment_id' => $id , 'flag' => 2 ))->get();
                        if ( $dislikecomment )
                        {
                            echo json_encode(array( 'status' => 1 , 'msg' => "comment disliked successfully" , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                        } else
                        {
                            echo json_encode(array( 'status' => 0 , 'msg' => Config::get('constant.TRY_MESSAGE') , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                        }
                    }
                } else
                {
                    return redirect('/index');
                }
            }
            catch ( \exception $e )
            {
                echo json_encode(array( 'status' => 0 , 'msg' => $e->getMessage() ));
            }
            
        }
        //===========================================================================================//
        public function loadmoreallmeeting(Request $request) {
            try
		{
                    if ($request) {
                            $offset = $request->get('offset');
                            $query = Meeting::withCount('meetingUsers')->has('meetingUsers.UserDetail')->orderByDesc('id');
                            if (!empty($request->get('search_text'))) {
                                    $search_text = $request->get('search_text');
                                    $query->whereNested(function($q) use ($search_text) {
                                        $q->where('meeting_title', 'like', '%' . $search_text . '%');
                                        $q->orWhere('meeting_description', 'like', '%' . $search_text . '%');
                                    });
                                    //$query->where('post_title', 'like', '%' . $search_text . '%');
                                    //$query->orWhere('post_description', 'like', '%' . $search_text . '%');
                            }
                            $count_allMeetings = count($query->get());
                            $allMeetings = $query->offset($offset)->limit(POST_DISPLAY_LIMIT)->get();
                            //echo "<pre>";print_r($posts);die();
                            $html = view::make($this->folder . '.meeting.ajaxallmeeting', compact('allMeetings', 'count_allMeetings'));
                            $output = array('html' => $html->render(), 'count' => $count_allMeetings);
                            return $output;
                    } else {
                            return redirect('/index')->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
                    }
            } catch (\exception $e) {
                    DB::rollback();

                    return Redirect::back()->with('err_msg', $e->getMessage());
            }
        }
        public function loadmoremymeeting(Request $request) {
            try
		{
                    if ($request) {
                            $offset = $request->get('offset');
                            $user_id     = Auth::user()->id;
                            $user_meetings = MeetingUser::where('user_id', $user_id)->pluck('meeting_id')->toArray();
                            $query = Meeting::withCount('meetingUsers')->whereIn('id', $user_meetings)->has('meetingUsers.UserDetail')->orderByDesc('id');
                            if (!empty($request->get('search_text'))) {
                                    $search_text = $request->get('search_text');
                                    $query->whereNested(function($q) use ($search_text) {
                                        $q->where('meeting_title', 'like', '%' . $search_text . '%');
                                        $q->orWhere('meeting_description', 'like', '%' . $search_text . '%');
                                    });
                            }
                            $count_myMeetings = count($query->get());
                            $myMeetings = $query->offset($offset)->limit(POST_DISPLAY_LIMIT)->get();
                            $html = view::make($this->folder . '.meeting.ajaxmymeeting', compact('myMeetings', 'count_myMeetings'));
                            $output = array('html' => $html->render(), 'count' => $count_myMeetings);
                            return $output;
                    } else {
                            return redirect('/index')->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
                    }
            } catch (\exception $e) {
                    DB::rollback();

                    return Redirect::back()->with('err_msg', $e->getMessage());
            }
        }
    }
