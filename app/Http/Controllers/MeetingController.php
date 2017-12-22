<?php
    
    namespace App\Http\Controllers;
    
    use App\Company;
    use App\Group;
    use App\GroupUser;
    use App\Meeting;
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
            return view($this->folder . '.meeting.index');
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
                
                if ( $meeting->save() ) {
                    
                    $group        = $users = [];
                    $meeting_id   = $meeting->id;
                    $meetingUsers = [ [ 'user_id' => $currUser->id, 'is_admin' => 1, 'group_id' => 0, 'meeting_id' => $meeting_id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ] ];
                    foreach ( $request->employees as $key => $val ) {
                        if ( strpos($val, 'group_') !== FALSE )
                            $group[] = substr($val, 6);
                        else
                            $users[] = $val;
                    }
                    if ( !empty($group) ) {
                        foreach ( $group as $key => $val ) {
                            $now         = Carbon::now();
                            $group_id    = $val;
                            $group_users = GroupUser::where('group_id', $group_id)->where('company_id', $company_id)->pluck('user_id')->toArray();
                            
                            foreach ( $group_users as $k => $v ) {
                                $is_admin = 0;
                                if ( $v != $currUser->id ) {
                                    $meetingUsers[] = [ 'user_id' => $v, 'is_admin' => $is_admin, 'group_id' => $group_id, 'meeting_id' => $meeting_id, 'created_at' => $now, 'updated_at' => $now ];
                                }
                            }
                        }
                    }
                    
                    if ( !empty($users) ) {
                        
                        $now = Carbon::now();
                        foreach ( $users as $key => $val ) {
                            $is_admin = 0;
                            if ( $val != $currUser->id ) {
                                $meetingUsers[] = [ 'user_id' => $val, 'is_admin' => $is_admin, 'group_id' => $group_id, 'meeting_id' => $meeting_id, 'created_at' => $now, 'updated_at' => $now ];
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
            $meeting = Meeting::with(['meetingCreator' , 'meetingUser', 'meetingUser.following', 'meetingAttachment', 'meetingComment', 'meetingComment.commentUser', 'meetingComment.commentAttachment', 'meetingComment.commentReply', 'meetingComment.commentReply.commentReplyUser' ])->where('id', $id)->first();
            
            $meeting_user_ids = array_values(array_unique(MeetingUser::where('meeting_id', $meeting->id)->pluck('user_id')->toArray()));
            $meeting_users    = User::whereIn('id', $meeting_user_ids)->get();
            
//             $comments = Meeting::with()->where('id', $id)->get();

//                ->select('*',DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
            
            return view($this->folder . '.meeting.detail', compact('meeting', 'meeting_users', 'comments'));
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
            //
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
            //
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
                $deleteBtn = '<a href="javascript:void(0);" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
                
                return $showBtn . " | " . $deleteBtn;
            })->rawColumns([ 'actions' ])->make(TRUE);
//            dd($meetings);
        }
        
        public function saveComment( $id, Request $request )
        {
            //dd($request);
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
                    
                    $meetingComment               = new MeetingComment;
                    $meetingComment->user_id      = $user_id;
                    $meetingComment->meeting_id   = $id;
                    $meetingComment->comment_text = $comment_text;
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
                        $attachment->meeting_id         = $id;
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
                DB::rollback();
                
                return Redirect::back()->with('err_msg', $e->getMessage());
            }
        }
        
        public function deleteComment( $id = null )
        {
            if ( MeetingAttachment::where(array( 'type_id' => $id, 'type' => 2 ))->exists() ) {
                MeetingAttachment::where(array( 'type_id' => $id, 'type' => 2 ))->delete();
            }
            $deleteComment = MeetingComment::where('id', $id)->delete();
            if ( $deleteComment ) {
                return Redirect::back()->with('success', 'Comment deleted successfully');
            } else {
                return Redirect::back()->with('err_msg', '' . Config::get('constant.TRY_MESSAGE'));
            }
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
                        'file_upload'  => 'sometimes|required|email|max:2048'
                    ];
                    break;
            }
            return  Validator::make($request->all(), $rulesArray);
        }
    }
