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
    
    class MeetingController extends Controller
    {
        protected $folder;
        
        public function __construct()
        {
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
            //
            $currUser = Auth::user();
            $user_id  = $currUser->id;
            
            /*fetch list of meeting's id which current user is part of*/
            $user_meetings = MeetingUser::where('user_id', $user_id)->pluck('meeting_id')->toArray();
            
            /*
             * Private meetings will only be included in listing if user is part of it.
             * So select meeting's id user is currently in and then check against the primary_key in `meetings` table
             *
             * */
            $allMeetings = Meeting::withCount('meetingUsers')->where('privacy',0)->orderByDesc('id')->get();
            $myMeetings = Meeting::with('meetingCreator')->withCount('meetingUsers')->where('privacy', 0)->orWhereIn('id', $user_meetings)->orderByDesc('id')->get();
//    dd($meetings);
            return view($this->folder . '.meeting.index' , compact('myMeetings','allMeetings'));
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
//            dd($request->all());
            DB::beginTransaction();
            try {
                $currUser   = Auth::user();
                $company_id = $request->company_id;
                $privacy    = ($request->privacy[ 0 ] == 'public') ? 0 : 1;
                
                $meeting                      = new Meeting;
                $meeting->meeting_title       = $request->meeting_title;
                $meeting->meeting_description = $request->meeting_description;
                $meeting->privacy             = $privacy;
                $meeting->created_by          = $currUser->id;
//                dd($meeting);
                if ( $meeting->save() ) {
//                    dd("abc");
                    $group        = $users = [];
                    $meeting_id   = $meeting->id;
                    $meetingUsers = [ ['meeting_id' => $meeting->id , 'user_id' => $currUser->id, 'is_admin' => 1, 'group_id' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ] ];
                    if ( !empty($request->employees) )
                    {
                        foreach ( $request->employees as $emp )
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
                                $meetingUsers[] = [ 'meeting_id' => $meeting->id , 'user_id' => $val, 'is_admin' => $is_admin, 'group_id' => $group_id, 'created_at' => $now, 'updated_at' => $now ];
                            }
                        }
                    }
//                    dd($meetingUsers);
                    
                    if ( MeetingUser::insert($meetingUsers) ) {
                        DB::commit();
                        return redirect()->route('meeting.index')->with('success', 'Meeting has been created successfully.');
                    } else {
                        DB::rollBack();
                    }
                } else {
                    DB::rollBack();
                    
                    return redirect()->back()->with('err_msg', 'Some error occurred.')->withInput();
                }
            }
            catch ( Exception $ex ) {
                
                DB::rollBack();
                
                return redirect()->back()->with('err_msg', $ex->getMessage())->withInput();
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
            $meeting = Meeting::with(['meetingCreator' , 'meetingUser', 'meetingUser.following', 'meetingAttachment', 'meetingAttachment.attachmentUser', 'meetingComment', 'meetingComment.commentUser', 'meetingComment.commentAttachment', 'meetingComment.commentReply', 'meetingComment.commentReply.commentReplyUser' ])->where('id', $id)->first();
            $type_ids = MeetingComment::where('meeting_id',$meeting->id)->pluck('id')->toArray();
            $uploadedFiles = MeetingAttachment::with('attachmentUser')->whereIn('type_id',$type_ids)->orderBy('created_at','ASC')->get();
            $meeting_user_ids = array_values(array_unique(MeetingUser::where('meeting_id', $meeting->id)->pluck('user_id')->toArray()));
            $meeting_users    = User::whereIn('id', $meeting_user_ids)->get();
            if($meeting->privacy == '1')
            {
                if( !in_array( Auth::user()->id , $meeting_user_ids ) )
                    return Redirect::route('meeting.index')->with('err_msg' , 'You are not allowed to access a private meeting as you not a part of it.');
            }
//            return $uploadedFiles = MeetingAttachment::with(['attachmentUser'])->whereIn('user_id',$meeting_user_ids)->get();
//             $comments = Meeting::with()->where('id', $id)->get();

//                ->select('*',DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
//            dd($meeting);
            return view($this->folder . '.meeting.detail', compact('meeting', 'meeting_users', 'meeting_user_ids' , 'comments','uploadedFiles'));
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
            $meeting = Meeting::with(['meetingUser','meetingAttachment'])->where('id',$id)->first();
            if($currUser->id != $meeting->created_by)
            {
                return back();
            }
//            dd($meeting);
            if(!empty($meeting))
            {
                return view($this->folder.'.meeting.edit' , compact('meeting'));
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
            $id = Helpers::decode_url($id);
            $meeting = Meeting::find($id);
            
            $privacy                      = ($request->privacy[ 0 ] == 'public') ? 0 : 1;
            $meeting->meeting_title       = $request->input('meeting_title');
            $meeting->meeting_description = $request->input('meeting_description');
            $meeting->privacy             = $privacy;
            
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
                DB::commit();
                return Redirect::route('meeting.index')->with('success','Meeting details has been saved successfully.');
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
        
        public function deleteComment( Request $request )
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
        //===========================================================================================//
    }
