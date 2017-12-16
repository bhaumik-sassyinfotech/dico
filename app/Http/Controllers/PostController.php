<?php
    
    namespace App\Http\Controllers;
    
    use Exception;
    use Helpers;
    use Illuminate\Http\Request;
    use App\Company;
    use App\Post;
    use App\PostLike;
    use App\Attachment;
    use App\Comment;
    use App\CommentLike;
    use App\CommentReply;
    use App\Tag;
    use App\PostTag;
    use App\Group;
    use DB;
    use Validator;
    use Redirect;
    use Config;
    use Carbon;
    use Yajra\Datatables\Datatables;
    use Auth;
    
    
    class PostController extends Controller
    {
        
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        //protected $table = 'room';
        public $folder;
        
        public function __construct(Request $request)
        {
            $this->middleware(function ($request , $next) {
                if ( Auth::user()->role_id == 1 )
                {
                    $this->folder = 'superadmin';
                } else if ( Auth::user()->role_id == 2 )
                {
                    $this->folder = 'companyadmin';
                } else if ( Auth::user()->role_id == 3 )
                {
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
        
        
        public function create()
        {
            if ( Auth::user() )
            {
                $company = Company::where('id' , Auth::user()->company_id)->first();
                $groups = Group::where('company_id' , Auth::user()->company_id)->get();
                
                return view($this->folder . '.post.create' , compact('company','groups'));
            } else
            {
                return redirect('/index');
            }
        }
        
        public function store(Request $request)
        {
            try
            {
                if ( Auth::user() )
                {
                    $this->validate($request , [
                        'post_type'  => 'required' ,
                        'post_title' => 'required' ,
                    ]);
                    if ( $request->input('is_anonymous') )
                    {
                        $is_anonymous = 1;
                    } else
                    {
                        $is_anonymous = 0;
                    }
                    DB::beginTransaction();
                    $post                   = new Post;
                    $post->company_id       = Auth::user()->company_id;
                    $post->post_title       = $request->input('post_title');
                    $post->post_description = $request->input('post_description');
                    $post->post_type        = $request->input('post_type');
                    $post->user_id          = Auth::user()->id;
                    $post->is_anonymous     = $is_anonymous;
                    //=== groups saving start ===//
                    $post_groups = $request->input('user_groups');
                    if(!empty($post_groups)) {
                        $groups = implode(",", $post_groups);
                        $post->group_id     = $groups;
                    }
                    //=== groups saving end ===//
                    $post->save();
                    $file = $request->file('file_upload');
                    if ( $file != "" )
                    {
                        $postData = array();
                        //echo "here";die();
                        $fileName        = $file->getClientOriginalName();
                        $extension       = $file->getClientOriginalExtension();
                        $folderName      = '/uploads/';
                        $destinationPath = public_path() . $folderName;
                        $safeName        = str_random(10) . '.' . $extension;
                        $file->move($destinationPath , $safeName);
                        $attachment            = new Attachment;
                        $attachment->file_name = $safeName;
                        $attachment->type      = 1;
                        $attachment->type_id   = $post->id;
                        $attachment->user_id   = Auth::user()->id;
                        $attachment->save();
                        // $attachment = Attachment::insert($postData);
                    }
                    //=== tags saving start ===//
                    $post_tags = $request->input('post_tags');
                    if(!empty($post_tags)) {
                        $tags = explode(",", $post_tags);
                        foreach($tags as $tag) {
                            $existTag = Tag::where("tag_name",$tag)->first();
                            if($existTag) {
                                $tag_id = $existTag->id;
                            } else {
                                $tag_id = Tag::insertGetId(array("tag_name"=>$tag,"created_at"=>Carbon\Carbon::now()));
                            }
                            $post_tags = PostTag::insert(array("post_id"=>$post->id,"tag_id"=>$tag_id,"created_at"=>Carbon\Carbon::now()));
                        }
                    }
                    //=== tagss saving end ===//
                    DB::commit();
                    if ( $post )
                    {
                        return redirect()->route('post.index')->with('success' , 'Post ' . Config::get('constant.ADDED_MESSAGE'));
                    } else
                    {
                        return redirect()->route('post.index')->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE'));
                    }

//                    return $next($request);
                }
            }
            catch ( \exception $e )
            {
                DB::rollback();
                
                return Redirect::back()->with('err_msg' , $e->getMessage());
            }
        }
        
        /**
         * Show the application dashboard.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            //dd("here");
            if ( Auth::user() )
            {
//                $posts = Post::withCount('postLike')->with('postLike')->get();
//                dd($posts);
                $posts = Post::with(['postUser','postLike','postDisLike','postComment', 'postUserLike' => function ($q) {
                    return $q->where('user_id' , Auth::user()->id);
                } ,'postUserDisLike' => function ($q) {
                   return $q->where('user_id' , Auth::user()->id);
                } ,'postAttachment'])->withCount('postLike')->whereNULL('deleted_at')->where('company_id' , Auth::user()->company_id)->orderBy('post_like_count','desc')->get()->toArray();
            } else
            {
                return redirect('/index');
            }
            
           // dd($posts);
            return view($this->folder . '.post.index' , compact('posts'));
        }
        
        
        public function update($id , Request $request)
        {
            try
            {
                if ( Auth::user() )
                {
                    $this->validate($request , [
                        'post_type'  => 'required' ,
                        'post_title' => 'required' ,
                    ]);
                    $post = new Post;
                    if ( $request->input('is_anonymous') )
                    {
                        $is_anonymous = 1;
                    } else
                    {
                        $is_anonymous = 0;
                    }
                    DB::beginTransaction();
                    $postData = array( 'company_id' => Auth::user()->company_id , 'post_title' => $request->input('post_title') , 'post_description' => $request->input('post_description') , 'is_anonymous' => $is_anonymous , 'post_type' => $request->input('post_type') , 'updated_at' => Carbon\Carbon::now() );
                   //=== groups saving start ===//
                    $post_groups = $request->input('user_groups');
                    if(!empty($post_groups)) {
                        $groups = implode(",", $post_groups);
                        $postData['group_id'] = $groups;
                    }
                    //=== groups saving end ===//
                    $res      = $post->where('id' , $id)->update($postData);
                    $file     = $request->file('file_upload');
                    if ( $file != "" )
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
                        $attachment              = Attachment::insert($postData);
                    }
                    $post_tags = $request->input('post_tags');
                    if(!empty($post_tags)) {
                        $tags = explode(",", $post_tags);
                        $get_tag = PostTag::where('post_id',$id)->forceDelete();
                        
                        foreach($tags as $tag) {
                            $existTag = Tag::where("tag_name",$tag)->first();
                            if($existTag) {
                                $tag_id = $existTag->id;
                            } else {
                                $tag_id = Tag::insertGetId(array("tag_name"=>$tag,"created_at"=>Carbon\Carbon::now()));
                            }
                            $post_tags = PostTag::insert(array("post_id"=>$id,"tag_id"=>$tag_id,"created_at"=>Carbon\Carbon::now()));
                        }
                    }
                    
                    DB::commit();
                    if ( $res )
                    {
                        return redirect()->route('post.index')->with('success' , 'Post ' . Config::get('constant.UPDATE_MESSAGE'));
                    } else
                    {
                        return redirect()->route('post.index')->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE'));
                    }
                } else
                {
                    return redirect('/index');
                }
            }
            catch ( \exception $e )
            {
                DB::rollback();
                
                return Redirect::back()->with('err_msg' , $e->getMessage());
            }
        }
        
        public function edit($id)
        {
            
            $id = Helpers::decode_url($id);
            if ( Auth::user() )
            {
                $company = Company::where('id' , Auth::user()->company_id)->first();
                $groups = Group::where('company_id' , Auth::user()->company_id)->get();
                $post = Post::with('postUser')->with(['postAttachment','postTag.tag'])->whereNULL('deleted_at')->where('id' , $id)->first();
                //dd($post);
                return view($this->folder . '.post.edit' , compact('post', 'groups' , 'company'));
            } else
            {
                return redirect('/index');
            }
        }
        
        
        public function destroy($id)
        {
            //
        }
        
        
        public function get_post(Request $request)
        {
            $post = new Post;
            $res  = Post::with('postUser')->select('*' , DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
                ->whereNULL('deleted_at')->orderBy('id','desc');
            
            //dd($res);
            return Datatables::of($res)->addColumn('actions' , function ($row) {
                return '<a href="' . route('post.edit' , [ $row->id ]) . '" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a> | <a href="javascript:;" onclick="deletepost(' . $row->id . ')" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
            })->editColumn('created_at' , function ($user) {
                return $user->created_at->format('d/m/Y');
            })->rawColumns([ 'actions' ])->make(TRUE);
        }
        
        public function like_post($id) {
        try{
            if(Auth::user()) {
                $user_id = Auth::user()->id;
                $postlike = PostLike::where(array('user_id'=>$user_id,'post_id'=>$id))->first();
                if($postlike)
                {
                    if($postlike->flag == 1) {
                        $deleteLike = $postlike->forceDelete();
                        $likepost = PostLike::where(array('post_id'=>$id,'flag'=>1))->get();
                        $dislikepost = PostLike::where(array('post_id'=>$id,'flag'=>2))->get();
                        if($deleteLike) {
                            echo json_encode(array('status' => 0, 'msg' => "Remove post liked successfully",'likecount'=>count($likepost),'dislikecount'=>count($dislikepost)));
                        }else {
                            echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE'),'likecount'=>count($likepost),'dislikecount'=>count($dislikepost)));
                        }
                    } else {
                        $likepost = PostLike::where(array('user_id'=>$user_id,'post_id'=>$id))->update(array('flag'=>1));
                        $likepost = PostLike::where(array('post_id'=>$id,'flag'=>1))->get();
                        $dislikepost = PostLike::where(array('post_id'=>$id,'flag'=>2))->get();
                        if($likepost) {
                            echo json_encode(array('status' => 1, 'msg' => "post liked successfully",'likecount'=>count($likepost),'dislikecount'=>count($dislikepost)));
                        }else {
                            echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE'),'likecount'=>count($likepost),'dislikecount'=>count($dislikepost)));
                        }
                    }
                    
                }else {
                    $likepost = PostLike::insert(array('user_id'=>$user_id,'post_id'=>$id,'flag'=>1));
                    $likepost = PostLike::where(array('post_id'=>$id,'flag'=>1))->get();
                    $dislikepost = PostLike::where(array('post_id'=>$id,'flag'=>2))->get();
                    if($likepost) {
                        echo json_encode(array('status' => 1, 'msg' => "post liked successfully",'likecount'=>count($likepost),'dislikecount'=>count($dislikepost)));
                    }else {
                        echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE'),'likecount'=>count($likepost),'dislikecount'=>count($dislikepost)));
                    }
                }
            }else {
               return redirect('/index'); 
            }
        }catch (\exception $e) {
            echo json_encode(array('status' => 0,'msg' => $e->getMessage()));
        }
        
    }
    
    public function dislike_post($id) {
        try{
            if(Auth::user()) {
                $user_id = Auth::user()->id;
                $postdislike = PostLike::where(array('user_id'=>$user_id,'post_id'=>$id))->first();
                if($postdislike)
                {
                    if($postdislike->flag == 2) {
                        $deletedislike = $postdislike->forceDelete();
                        $likepost = PostLike::where(array('post_id'=>$id,'flag'=>1))->get();
                        $dislikepost = PostLike::where(array('post_id'=>$id,'flag'=>2))->get();
                        if($deletedislike) {
                            echo json_encode(array('status' => 0, 'msg' => "Remove post disliked successfully",'likecount'=>count($likepost),'dislikecount'=>count($dislikepost)));
                        }else {
                            echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE'),'likecount'=>count($likepost),'dislikecount'=>count($dislikepost)));
                        }
                    }else {
                        $dislikepost = PostLike::where(array('user_id'=>$user_id,'post_id'=>$id))->update(array('flag'=>2));
                        $likepost = PostLike::where(array('post_id'=>$id,'flag'=>1))->get();
                        $dislikepost = PostLike::where(array('post_id'=>$id,'flag'=>2))->get();
                        if($dislikepost) {
                            echo json_encode(array('status' => 1, 'msg' => "post disliked successfully",'likecount'=>count($likepost),'dislikecount'=>count($dislikepost)));
                        }else {
                            echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE'),'likecount'=>count($likepost),'dislikecount'=>count($dislikepost)));
                        }
                    }
                    
                }else {
                    $dislikepost = PostLike::insert(array('user_id'=>$user_id,'post_id'=>$id,'flag'=>2));
                    $likepost = PostLike::where(array('post_id'=>$id,'flag'=>1))->get();
                    $dislikepost = PostLike::where(array('post_id'=>$id,'flag'=>2))->get();
                    if($dislikepost) {
                        echo json_encode(array('status' => 1, 'msg' => "post disliked successfully",'likecount'=>count($likepost),'dislikecount'=>count($dislikepost)));
                    }else {
                        echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE'),'likecount'=>count($likepost),'dislikecount'=>count($dislikepost)));
                    }
                }
            }else {
               return redirect('/index'); 
            }
        }catch (\exception $e) {
            echo json_encode(array('status' => 0,'msg' => $e->getMessage()));
        }
        
    }
        
   
    
    public function viewpost($id) {
        $id = Helpers::decode_url($id);
        $post = Post::with('postUser','postUser.following')->with('postLike')->with('postDisLike')->with(['postUserLike' => function($q) {
                    $q->where('user_id',  Auth::user()->id)->first(); 
                }])->with(['postUserDisLike' => function($q) {
                        $q->where('user_id',  Auth::user()->id)->first(); // '=' is optional
                    }])->with('postAttachment')->with(['postComment','postComment.commentUser','postComment.commentAttachment','postComment.commentLike','postComment.commentDisLike','postComment.commentReply','postComment.commentReply.commentReplyUser','postComment.commentUserLike' => function($q) {
                    $q->where('user_id',  Auth::user()->id)->first(); 
                },'postComment.commentUserDisLike' => function($q) {
                    $q->where('user_id',  Auth::user()->id)->first(); 
                }])->select('*',DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
                ->whereNULL('deleted_at')->where('id',$id)->first();
                //dd($post);
        if($post['postUser']['company_id'] == Auth::user()->company_id) {        
            return view($this->folder.'.post.view', compact('post'));
        }else {
            return redirect('/index'); 
        }
    }
    
    public function savecomment($id,Request $request) {
        //dd($request);
        try{
            if(Auth::user()) {
                $user_id = Auth::user()->id;
                $comment_text = $request->input('comment_text');
                if($request->input('is_anonymous')) {
                    $is_anonymous = 1;
                } else {
                    $is_anonymous = 0;
                }
                DB::beginTransaction();
                $postData = array("user_id"=>$user_id,"post_id"=>$id,"comment_text"=>$comment_text,"is_anonymous"=>$is_anonymous);
                return $res = Comment::insertGetId($postData);
                $file = $request->file('file_upload');
                if ($file != "") {
                    $fileName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $folderName = '/uploads/';
                    $destinationPath = public_path() . $folderName;
                    $safeName = str_random(10) . '.' . $extension;
                    $file->move($destinationPath, $safeName);
                    $attachment = new Attachment;
                    $attachment->file_name = $safeName;
                    $attachment->type = 2;
                    $attachment->type_id = $res;
                    $attachment->user_id = Auth::user()->id;
                    $attachment->save();
                   // $attachment = Attachment::insert($postData);
                }
                DB::commit();
                if($res) {
                    return Redirect::back()->with('success', 'Comment '.Config::get('constant.ADDED_MESSAGE'));
                }else {
                    return Redirect::back()->with('err_msg', 'Please try again.');
                }
            }
            else {
               return redirect('/index');  
            }
        }catch (\exception $e) {
            DB::rollback();
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }
    public function deletecomment($id = null) {
        if(Attachment::where(array('type_id'=>$id,'type'=>2))->exists()) {
            Attachment::where(array('type_id'=>$id,'type'=>2))->delete();
        }
        $deleteComment = Comment::where('id', $id)->delete();
        if($deleteComment) {
            return Redirect::back()->with('success', 'Comment deleted successfully');
        }else {
            return Redirect::back()->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
        }
    }
    
    public function like_comment($id)
        {
            try
            {
                if ( Auth::user() )
                {
                    $f           = 0;
                    $user_id     = Auth::user()->id;
                    $commentlike = CommentLike::where(array( 'user_id' => $user_id , 'comment_id' => $id ))->first();
                    if ( $commentlike )
                    {
                        if ( $commentlike->flag == 1 )
                        {
                            $deletelike     = $commentlike->forceDelete();
                            $likecomment    = CommentLike::where(array( 'comment_id' => $id , 'flag' => 1 ))->get();
                            $dislikecomment = CommentLike::where(array( 'comment_id' => $id , 'flag' => 2 ))->get();
                            if ( $deletelike )
                            {
                                echo json_encode(array( 'status' => 0 , 'msg' => "Remove comment Liked successfully" , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                            } else
                            {
                                echo json_encode(array( 'status' => 0 , 'msg' => Config::get('constant.TRY_MESSAGE') , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                            }
                        } else
                        {
                            $likecomment    = CommentLike::where(array( 'user_id' => $user_id , 'comment_id' => $id ))->update(array( 'flag' => 1 ));
                            $likecomment    = CommentLike::where(array( 'comment_id' => $id , 'flag' => 1 ))->get();
                            $dislikecomment = CommentLike::where(array( 'comment_id' => $id , 'flag' => 2 ))->get();
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
                        $likecomment    = CommentLike::insert(array( 'user_id' => $user_id , 'comment_id' => $id , 'flag' => 1 ));
                        $likecomment    = CommentLike::where(array( 'comment_id' => $id , 'flag' => 1 ))->get();
                        $dislikecomment = CommentLike::where(array( 'comment_id' => $id , 'flag' => 2 ))->get();
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
        
        public function dislike_comment($id)
        {
            try
            {
                if ( Auth::user() )
                {
                    $user_id        = Auth::user()->id;
                    $commentdislike = CommentLike::where(array( 'user_id' => $user_id , 'comment_id' => $id ))->first();
                    if ( $commentdislike )
                    {
                        if ( $commentdislike->flag == 2 )
                        {
                            $deletedislike  = $commentdislike->forceDelete();
                            $likecomment    = CommentLike::where(array( 'comment_id' => $id , 'flag' => 1 ))->get();
                            $dislikecomment = CommentLike::where(array( 'comment_id' => $id , 'flag' => 2 ))->get();
                            if ( $deletedislike )
                            {
                                echo json_encode(array( 'status' => 0 , 'msg' => "Remove comment disiked successfully" , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                            } else
                            {
                                echo json_encode(array( 'status' => 0 , 'msg' => Config::get('constant.TRY_MESSAGE') , 'likecount' => count($likecomment) , 'dislikecount' => count($dislikecomment) ));
                            }
                        } else
                        {
                            $dislikecomment = CommentLike::where(array( 'user_id' => $user_id , 'comment_id' => $id ))->update(array( 'flag' => 2 ));
                            $likecomment    = CommentLike::where(array( 'comment_id' => $id , 'flag' => 1 ))->get();
                            $dislikecomment = CommentLike::where(array( 'comment_id' => $id , 'flag' => 2 ))->get();
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
                        $dislikecomment = CommentLike::insert(array( 'user_id' => $user_id , 'comment_id' => $id , 'flag' => 2 ));
                        $likecomment    = CommentLike::where(array( 'comment_id' => $id , 'flag' => 1 ))->get();
                        $dislikecomment = CommentLike::where(array( 'comment_id' => $id , 'flag' => 2 ))->get();
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
        public function comment_solution(Request $request) {
        try{
            if(Auth::user()) {
                $comment_id = $request->input('comment_id');
                $user_id = $request->input('user_id');
                $post_id = $request->input('post_id');
                $check = Comment::where(array('post_id'=>$post_id,'is_correct'=>1))->first();
                if($check) {
                    if($check->user_id == $user_id) {
                        echo json_encode(array('status' => 2,'msg' => "Solution already marked"));
                    }else {
                        echo json_encode(array('status' => 0,'msg' => "Solution already marked"));
                    }
                }else{
                    $answer = Comment::where('id',$comment_id)->update(array('is_correct'=>1,'is_correct_by_user'=>$user_id));
                    if($answer) {
                        echo json_encode(array('status' => 1, 'msg' => "answer marked successfully"));
                    }else {
                        echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE')));
                    }
                } /*else {
                    $answer = Comment::where('id',$comment_id)->update(array('is_correct'=>0,'is_correct_by_user'=>0));
                    if($answer) {
                        echo json_encode(array('status' => 0, 'msg' => "answer marked successfully"));
                    }else {
                        echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE')));
                    }
                }*/
                
            }else {
               return redirect('/index'); 
            }
        }catch (\exception $e) {
             echo json_encode(array('status' => 0,'msg' => $e->getMessage()));
        }
    }
    
    
    public function idea_update($id , Request $request)
    {
        $this->validate($request , [
            'post_type'  => 'required' ,
            'post_title' => 'required' ,
        ]);
        DB::beginTransaction();
        try
        {
            $currUser               = Auth::user();
            $post                   = Post::find($id);
            $post->post_title       = $request->post_title;
            $post->post_type        = $request->post_type;
            $post->post_description = $request->post_description;
            $post->user_id          = $currUser->id;
            $post->is_anonymous     = $request->get('is_anonymous') ? 1 : 0;
            if ( $post->save() )
            {
                $file = $request->file('file_upload');
                if ( $file != "" )
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
                    $postData[ 'user_id' ]   = $currUser->id;
//                        $attachment              = Attachment::insert($postData);
                    $attachment              = Attachment::where('type_id',$id)->where('type',1)->where('user_id',$currUser->id);
                    $now = Carbon\Carbon::now();
                    if($attachment->first())
                    {
                        $attachment->update(['file_name' => $postData[ 'file_name' ] , 'updated_at' => $now]);
                    } else {
                        
                        $postData['created_at'] = $postData['updated_at'] = $now;
                        Attachment::insert($postData);
                    }
                }
                DB::commit();
                return back();
            } else
            {
                DB::rollBack();
                return back()->withInput();
            }
        }
        catch ( Exception $ex )
        {
            DB::rollBack();
            return Redirect::back()->with('err_msg' , $ex->getMessage());
        }
    }
        
        public function idea_edit($id , Request $request)
        {
            
            $currUser  = Auth::user();
            $userId    = $currUser->id;
            $companyId = $currUser->company_id;
            
            $post = Post::where('id' , $id)->where('user_id' , $userId)->where('company_id' , $companyId)->with([ 'postUser' , 'postAttachment' ])->first();
            
            if($post == null )
                return redirect()->route('post.index')->with('err_msg',"You don't have permissions to edit this post");
            
//            dd($post);
            return view($this->folder . '.post.edit_idea_post' , compact('post'));
        }
        
        public function idea_show($id)
        {
            
            $currUser = Auth::user();
//            dd($currUser);
            $postViews = Helpers::postViews($id , $currUser->id);
            $post = Post::with('postUser','postUser.following')->with('postLike')->with('postDisLike')->with(['postUserLike' => function($q) {
                $q->where('user_id',  Auth::user()->id)->first();
            }])->with(['postUserDisLike' => function($q) {
                $q->where('user_id',  Auth::user()->id)->first(); // '=' is optional
            }])->with('postAttachment')->select('*',DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
                ->where('post_type','=','idea')->where('id',$id)->first();
//            dd($post);
            if($post == null)
                return redirect()->route('post.index')->with('err_msg',"You don't have permissions to edit this post");
            return view($this->folder . '.post.view_idea_post' , compact('post','postViews','currUser'));
        }
        
        public function change_status(Request $request)
        {
            
            $currUser = Auth::user();
            if ( $currUser->role_id < 3 )
            {
                $status = $request->get('idea_status');
                $reason = $request->get('idea_reason');
                $postId = $request->get('post_id');
                
                $res = Post::where('id' , $postId)->update([ 'idea_status' => $status , 'idea_reason' => $reason , 'idea_status_updated_by' => $currUser->id ]);
                if ( $res )
                    return response()->json([ 'status' => 1 , 'msg' => "Status of this idea has been set successfully." ]);
                else
                    return response()->json([ 'status' => 0 , 'msg' => "Failed to set the status of this idea." ]);
            }
            
            return response()->json([ 'status' => 0 , 'msg' => "[C->PC->c_s] This permission is only available to Admin or manager. " ]);
        }
        
        public function comment_reply(Request $request) {
            try
            {
                if(Auth::user()) {
                    $post_id = $request->input('post_id');
                    $user_id = Auth::user()->id;
                    $comment_reply = $request->input('comment_reply');
                    $is_anonymous = $request->input('is_anonymous');
                    $comment_id = $request->input('comment_id');
                    $srno = $request->input('srno');
                    
                    $postData = array("user_id"=>$user_id,"comment_id"=>$comment_id,"comment_reply"=>$comment_reply,"is_anonymous"=>$is_anonymous,"created_at"=>Carbon\Carbon::now());
                    $reply_id = CommentReply::insertGetId($postData);
                    if($reply_id) {
                        $commentReply = CommentReply::with('commentReplyUser')->where('id',$reply_id)->first()->toArray();
                            //print_r($post);
                        return view($this->folder . '.post.comment', compact('commentReply','srno'));    
                    }else {
                        echo json_encode(array('status' => 1,'msg' => Config::get('constant.TRY_MESSAGE')));
                    }
                } else {
                    return redirect('/index'); 
                }
            }
            catch (Exception $ex) {
                echo json_encode(array('status' => 2,'msg' => $ex->getMessage()));
            }
        }
        public function deletecommentReply($id = null) {
            $deleteCommentReply = CommentReply::where('id', $id)->delete();
            if($deleteCommentReply) {
                return Redirect::back()->with('success', 'Comment deleted successfully');
            }else {
                return Redirect::back()->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
            }
        }
        public function tags() {
            $tags = Tag::all();
            if($tags) {
               /* $tag_arr = array();
                foreach($tags as $tag) {
                    $tag_arr[] = $tag['tag_name'];
                }*/
                //$tagArr = implode(",", $tag_arr);
                //dd($tags);
                echo json_encode(array('status' => 1,'data' => $tags));
            } else {
                echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE')));
            }
        }
        
    }

?>
