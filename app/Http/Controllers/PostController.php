<?php
    namespace App\Http\Controllers;
    
    use App\PostView;
    use Exception;
    use Helpers;
    use Illuminate\Http\Request;
    use App\Post;
    use App\PostLike;
    use App\Attachment;
    use App\Comment;
    use App\CommentLike;
    use DB;
    use Illuminate\Http\Response;
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
        public function index()
        {
            //dd("here");
            if ( Auth::user() )
            {
                $posts = Post::with('postUser')->with('postLike')->with([ 'postUserLike' => function ($q) {
                    $q->where('user_id' , Auth::user()->id)->first(); // '=' is optional
                } ])->with('postAttachment')->select('*' , DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
                    ->whereNULL('deleted_at')->where('company_id' , Auth::user()->company_id)->get()->toArray();
            } else
            {
                return redirect('/index');
            }
            
            //dd($posts);
            return view($this->folder . '.post.index' , compact('posts'));
        }
        
        public function create()
        {
            return view($this->folder . '.post.create');
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
                    DB::commit();
                    if ( $post )
                    {
                        return redirect()->route('post.index')->with('success' , 'Post ' . Config::get('constant.ADDED_MESSAGE'));
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
            $post = Post::with('postUser')->with('postLike')->with('postDisLike')->with([ 'postUserLike' => function ($q) {
                $q->where('user_id' , Auth::user()->id)->first(); // '=' is optional
            } ])->with([ 'postUserDisLike' => function ($q) {
                $q->where('user_id' , Auth::user()->id)->first(); // '=' is optional
            } ])->with('postAttachment')->select('*' , DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
                ->whereNULL('deleted_at')->where('id' , $id)->first();
            
            return view($this->folder . '.post.edit' , compact('post'));
        }
        
        public function update(Request $request , $id)
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
        
        public function destroy($id)
        {
            //
        }
        
        
        public function get_post(Request $request)
        {
            $post = new Post;
            $res  = Post::with('postUser')->select('*' , DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
                ->whereNULL('deleted_at');
            
            //dd($res);
            return Datatables::of($res)->addColumn('actions' , function ($row) {
                return '<a href="' . route('post.edit' , [ $row->id ]) . '" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a> | <a href="javascript:;" onclick="deletepost(' . $row->id . ')" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
            })->editColumn('created_at' , function ($user) {
                return $user->created_at->format('d/m/Y');
            })->rawColumns([ 'actions' ])->make(TRUE);
        }
        
        public function like_post($id)
        {
            try
            {
                if ( Auth::user() )
                {
                    $user_id  = Auth::user()->id;
                    $postlike = PostLike::where(array( 'user_id' => $user_id , 'post_id' => $id ))->first();
                    if ( $postlike )
                    {
                        if ( $postlike->flag == 1 )
                        {
                            if ( $postlike->forceDelete() )
                            {
                                return Redirect::back()->with('success' , 'Remove post Liked successfully');
                            } else
                            {
                                return Redirect::back()->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE'));
                            }
                        } else
                        {
                            $likepost = PostLike::where(array( 'user_id' => $user_id , 'post_id' => $id ))->update(array( 'flag' => 1 , 'updated_at' => Carbon\Carbon::now() ));
                            if ( $likepost )
                            {
                                return Redirect::back()->with('success' , 'post disliked successfully');
                            } else
                            {
                                return Redirect::back()->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE'));
                            }
                        }
                        
                    } else
                    {
                        $likepost = PostLike::insert(array( 'user_id' => $user_id , 'post_id' => $id , 'flag' => 1 , 'created_at' => Carbon\Carbon::now() ));
                        if ( $likepost )
                        {
                            return Redirect::back()->with('success' , 'Post Liked successfully');
                        } else
                        {
                            return Redirect::back()->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE'));
                        }
                    }
                } else
                {
                    return redirect('/index');
                }
            }
            catch ( \exception $e )
            {
                return Redirect::back()->with('err_msg' , $e->getMessage());
            }
            
        }
        
        public function dislike_post($id)
        {
            try
            {
                if ( Auth::user() )
                {
                    $user_id     = Auth::user()->id;
                    $postdislike = PostLike::where(array( 'user_id' => $user_id , 'post_id' => $id ))->first();
                    if ( $postdislike )
                    {
                        if ( $postdislike->flag == 2 )
                        {
                            if ( $postdislike->forceDelete() )
                            {
                                return Redirect::back()->with('success' , 'Remove post disliked successfully');
                            } else
                            {
                                return Redirect::back()->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE'));
                            }
                        } else
                        {
                            $dislikepost = PostLike::where(array( 'user_id' => $user_id , 'post_id' => $id ))->update(array( 'flag' => 2 , 'updated_at' => Carbon\Carbon::now() ));
                            if ( $dislikepost )
                            {
                                return Redirect::back()->with('success' , 'post disliked successfully');
                            } else
                            {
                                return Redirect::back()->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE'));
                            }
                        }
                        
                    } else
                    {
                        $dislikepost = PostLike::insert(array( 'user_id' => $user_id , 'post_id' => $id , 'flag' => 2 , 'created_at' => Carbon\Carbon::now() ));
                        if ( $dislikepost )
                        {
                            return Redirect::back()->with('success' , 'Post disliked successfully');
                        } else
                        {
                            return Redirect::back()->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE'));
                        }
                    }
                } else
                {
                    return redirect('/index');
                }
            }
            catch ( \exception $e )
            {
                return Redirect::back()->with('err_msg' , $e->getMessage());
            }
            
        }
        
        public function viewpost($id)
        {
            $post = Post::with('postUser' , 'postUser.following')->with('postLike')->with([ 'postUserLike' => function ($q) {
                $q->where('user_id' , Auth::user()->id)->first();
            } ])->with('postAttachment')->with([ 'postComment' , 'postComment.commentUser' , 'postComment.commentAttachment' ])->select('*' , DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
                ->whereNULL('deleted_at')->where('id' , $id)->first();
            
            //dd($post);
            return view($this->folder . '.post.view' , compact('post'));
        }
        
        public function savecomment($id , Request $request)
        {
            //dd($request);
            try
            {
                if ( Auth::user() )
                {
                    $user_id      = Auth::user()->id;
                    $comment_text = $request->input('comment_text');
                    if ( $request->input('is_anonymous') )
                    {
                        $is_anonymous = 1;
                    } else
                    {
                        $is_anonymous = 0;
                    }
                    DB::beginTransaction();
                    $postData = array( "user_id" => $user_id , "post_id" => $id , "comment_text" => $comment_text , "is_anonymous" => $is_anonymous , "created_at" => Carbon\Carbon::now() );
                    $res      = Comment::insert($postData);
                    $file     = $request->file('file_upload');
                    if ( $file != "" )
                    {
                        $fileName        = $file->getClientOriginalName();
                        $extension       = $file->getClientOriginalExtension();
                        $folderName      = '/uploads/';
                        $destinationPath = public_path() . $folderName;
                        $safeName        = str_random(10) . '.' . $extension;
                        $file->move($destinationPath , $safeName);
                        $attachment            = new Attachment;
                        $attachment->file_name = $safeName;
                        $attachment->type      = 2;
                        $attachment->type_id   = $res->id;
                        $attachment->user_id   = Auth::user()->id;
                        $attachment->save();
                        // $attachment = Attachment::insert($postData);
                    }
                    DB::commit();
                    if ( $res )
                    {
                        return Redirect::back()->with('success' , 'Comment ' . Config::get('constant.ADDED_MESSAGE'));
                    } else
                    {
                        return Redirect::back()->with('err_msg' , $e->getMessage());
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
        
        public function deletecomment($id = null)
        {
            if ( Attachment::where(array( 'type_id' => $id , 'type' => 2 ))->exists() )
            {
                Attachment::where(array( 'type_id' => $id , 'type' => 2 ))->delete();
            }
            $deleteComment = Comment::where('id' , $id)->delete();
            if ( $deleteComment )
            {
                return Redirect::back()->with('success' , 'Comment successfully');
            } else
            {
                return Redirect::back()->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE'));
            }
        }
        
        public function like_comment($id)
        {
            try
            {
                if ( Auth::user() )
                {
                    $user_id     = Auth::user()->id;
                    $commentlike = CommentLike::where(array( 'user_id' => $user_id , 'comment_id' => $id ))->first();
                    if ( $commentlike )
                    {
                        if ( $postlike->flag == 1 )
                        {
                            if ( $postlike->forceDelete() )
                            {
                                return Redirect::back()->with('success' , 'Remove post Liked successfully');
                            } else
                            {
                                return Redirect::back()->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE'));
                            }
                        } else
                        {
                            $likepost = PostLike::where(array( 'user_id' => $user_id , 'post_id' => $id ))->update(array( 'flag' => 1 , 'updated_at' => Carbon\Carbon::now() ));
                            if ( $likepost )
                            {
                                return Redirect::back()->with('success' , 'post liked successfully');
                            } else
                            {
                                return Redirect::back()->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE'));
                            }
                        }
                        
                    } else
                    {
                        $likepost = PostLike::insert(array( 'user_id' => $user_id , 'post_id' => $id , 'flag' => 1 , 'created_at' => Carbon\Carbon::now() ));
                        if ( $likepost )
                        {
                            return Redirect::back()->with('success' , 'Post Liked successfully');
                        } else
                        {
                            return Redirect::back()->with('err_msg' , '' . Config::get('constant.TRY_MESSAGE'));
                        }
                    }
                } else
                {
                    return redirect('/index');
                }
            }
            catch ( \exception $e )
            {
                return Redirect::back()->with('err_msg' , $e->getMessage());
            }
            
        }
        
        public function idea_store(Request $request)
        {
            DB::beginTransaction();
            $validator = $this->validate($request , [
                'post_type'  => 'required' ,
                'post_title' => 'required' ,
            ]);
            try
            {
                $currUser               = Auth::user();
                $post                   = new Post;
                $post->post_title       = $request->post_title;
                $post->post_type        = strtolower($request->post_type);
                $post->post_description = $request->post_description;
                $post->user_id          = $currUser->id;
                $post->is_anonymous     = $request->get('is_anonymous') ? $request->get('is_anonymous') : 0;
                $file                   = $request->file('file_upload');
                if ( $file != "" )
                {
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
                    $attachment->user_id   = $currUser->id;
                    $attachment->save();
                }
                if ( $post->save() )
                {
                    DB::commit();
                    dd("Your post has been saved.");
                } else
                {
                    DB::rollBack();
                    dd("There was some error saving your post.");
                }
            }
            catch ( Exception $ex )
            {
                DB::rollBack();
                
                return back();
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
//                        $postData[ 'type' ]      = 1;
//                        $postData[ 'type_id' ]   = $id;
//                        $postData[ 'user_id' ]   = $currUser->id;
//                        $attachment              = Attachment::insert($postData);
                        $attachment              = Attachment::where('type_id',$id)->where('type',1)->where('user_id',$currUser->id)->update($postData);
                    }
                    DB::commit();

                    return back();
                } else
                {
                    DB::rollBack();
                    dd("There was some error saving your post.");
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
            
            return view($this->folder . '.post.edit_idea_post' , compact('post'));
        }
    
        public function idea_show($id)
        {
            
            $currUser = Auth::user();
            
            $postViews = Helpers::postViews($id , $currUser->id );
                $post = Post::with('ideaUser','postUser' , 'postUser.following')->with('postLike')->with([ 'postUserLike' => function ($q) {
                $q->where('user_id' , Auth::user()->id)->first();
            } ])->with('postAttachment')->with([ 'postComment' , 'postComment.commentUser' , 'postComment.commentAttachment' ])->select('*' , DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
                ->whereNULL('deleted_at')->where('id' , $id)->first();
    
            return view($this->folder . '.post.view_idea_post' , compact('post'));
        }
        
        public function change_status(Request $request)
        {
            
            $currUser = Auth::user();
            if($currUser->role_id < 3)
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
    }

?>
