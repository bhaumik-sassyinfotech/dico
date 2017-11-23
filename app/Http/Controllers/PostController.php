<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Post;
use App\PostLike;
use App\Attachment;
use App\Comment;
use App\CommentLike;
use DB;
use Validator;
use Redirect;
use Config;
use Carbon;
use Yajra\Datatables\Datatables;
use Auth;


class PostController extends Controller {
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //protected $table = 'room';
    public $folder;
    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            if(Auth::user()->role_id == 1) {
                $this->folder = 'superadmin';
            }else if(Auth::user()->role_id == 2) {
                $this->folder = 'companyadmin';
            }else if(Auth::user()->role_id == 3) {
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
        if(Auth::user()) { 
            $posts = Post::with('postUser')->with('postLike')->with('postDisLike')->with('postComment')->with(['postUserLike' => function($q) {
                        $q->where('user_id',  Auth::user()->id)->first(); // '=' is optional
                    }])->with(['postUserDisLike' => function($q) {
                        $q->where('user_id',  Auth::user()->id)->first(); // '=' is optional
                    }])->with('postAttachment')->select('*',DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
                    ->whereNULL('deleted_at')->where('company_id',Auth::user()->company_id)->get()->toArray();
        } else {
            return redirect('/index');
        }
        //dd($posts);
        return view($this->folder.'.post.index',compact('posts'));
    }
    
    public function create() {
        if(Auth::user()) { 
        $company = Company::where('id',Auth::user()->company_id)->first();
        return view($this->folder.'.post.create',compact('company'));
        } else {
            return redirect('/index');
        }
    }

    public function store(Request $request) {
        try {
            if(Auth::user()) { 
                $this->validate($request, [
                    'post_type' => 'required',
                    'post_title' => 'required'
                ]);
                if($request->input('is_anonymous')) {
                    $is_anonymous = 1;
                } else {
                    $is_anonymous = 0;
                }
                DB::beginTransaction();
                $post = new Post;
                $post->company_id = Auth::user()->company_id;
                $post->post_title = $request->input('post_title');
                $post->post_description = $request->input('post_description');
                $post->post_type = $request->input('post_type');
                $post->user_id = Auth::user()->id;
                $post->is_anonymous = $is_anonymous;
                $post->save();
                $file = $request->file('file_upload');
                if ($file != "") {
                    $postData = array();
                    //echo "here";die();
                    $fileName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $folderName = '/uploads/';
                    $destinationPath = public_path() . $folderName;
                    $safeName = str_random(10) . '.' . $extension;
                    $file->move($destinationPath, $safeName);
                    $attachment = new Attachment;
                    $attachment->file_name = $safeName;
                    $attachment->type = 1;
                    $attachment->type_id = $post->id;
                    $attachment->user_id = Auth::user()->id;
                    $attachment->save();
                   // $attachment = Attachment::insert($postData);
                }
                DB::commit();
                if ($post) {
                    return redirect()->route('post.index')->with('success', 'Post '.Config::get('constant.ADDED_MESSAGE'));
                } else {
                   return redirect()->route('post.index')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
                }
            }else {
                return redirect('/index');
            }
        }catch (\exception $e) {
            DB::rollback();
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }
    
    public function edit($id) {
        if(Auth::user()) { 
        $company = Company::where('id',Auth::user()->company_id)->first();
        $post = Post::with('postUser')->with('postLike')->with('postDisLike')->with(['postUserLike' => function($q) {
                    $q->where('user_id',  Auth::user()->id)->first(); // '=' is optional
                }])->with(['postUserDisLike' => function($q) {
                    $q->where('user_id',  Auth::user()->id)->first(); // '=' is optional
                }])->with('postAttachment')->select('*',DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
                ->whereNULL('deleted_at')->where('id',$id)->first();
        return view($this->folder.'.post.edit', compact('post','company'));
        } else {
            return redirect('/index');
        }
    }
    
    public function update(Request $request, $id) {
        try{
            if(Auth::user()) { 
                $this->validate($request, [
                        'post_type' => 'required',
                        'post_title' => 'required'
                    ]);
                $post = new Post;
                if($request->input('is_anonymous')) {
                    $is_anonymous = 1;
                } else {
                    $is_anonymous = 0;
                }
                DB::beginTransaction();
                $postData = array('company_id'=>Auth::user()->company_id,'post_title'=>$request->input('post_title'),'post_description'=>$request->input('post_description'),'is_anonymous'=>$is_anonymous,'post_type'=>$request->input('post_type'));
                $res = $post->where('id', $id)->update($postData);
                $file = $request->file('file_upload');
                if ($file != "") {
                    $postData = array();
                    //echo "here";die();
                    $fileName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $folderName = '/uploads/';
                    $destinationPath = public_path() . $folderName;
                    $safeName = str_random(10) . '.' . $extension;
                    $file->move($destinationPath, $safeName);
                    //$attachment = new Attachment;
                    $postData['file_name'] = $safeName;
                    $postData['type'] = 1;
                    $postData['type_id'] = $id;
                    $postData['user_id'] = Auth::user()->id;
                    $attachment = Attachment::insert($postData);
                }
                DB::commit();
                if ($res) {
                    return redirect()->route('post.index')->with('success', 'Post '.Config::get('constant.UPDATE_MESSAGE'));
                } else {
                    return redirect()->route('post.index')->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
                }
            }else {
               return redirect('/index'); 
            }
        }catch (\exception $e) {
            DB::rollback();
            return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }
    
    public function destroy($id) {
        //
    }
    
    
    public function get_post(Request $request) {
        $post = new Post;
        $res = Post::with('postUser')->select('*',DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
                ->whereNULL('deleted_at');
        //dd($res);
        return Datatables::of($res)->addColumn('actions' , function ( $row )
        {
            return '<a href="'.route('post.edit' , [ $row->id ]).'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a> | <a href="javascript:;" onclick="deletepost('.$row->id.')" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
        })->editColumn('created_at', function ($user) {
            return $user->created_at->format('d/m/Y');
        })->rawColumns(['actions'])->make(true);
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
        $post = Post::with('postUser','postUser.following')->with('postLike')->with(['postUserLike' => function($q) {
                    $q->where('user_id',  Auth::user()->id)->first(); 
                }])->with('postAttachment')->with(['postComment','postComment.commentUser','postComment.commentAttachment','postComment.commentLike','postComment.commentDisLike','postComment.commentUserLike' => function($q) {
                    $q->where('user_id',  Auth::user()->id)->first(); 
                },'postComment.commentUserDisLike' => function($q) {
                    $q->where('user_id',  Auth::user()->id)->first(); 
                }])->select('*',DB::raw('CASE WHEN status = "1" THEN "Active" ELSE "Closed" END AS post_status'))
                ->whereNULL('deleted_at')->where('id',$id)->first();
                //dd($post);
        return view($this->folder.'.post.view', compact('post'));
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
                $res = Comment::insert($postData);
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
                    $attachment->type_id = $res->id;
                    $attachment->user_id = Auth::user()->id;
                    $attachment->save();
                   // $attachment = Attachment::insert($postData);
                }
                DB::commit();
                if($res) {
                    return Redirect::back()->with('success', 'Comment '.Config::get('constant.ADDED_MESSAGE'));
                }else {
                    return Redirect::back()->with('err_msg', $e->getMessage());
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
            return Redirect::back()->with('success', 'Comment successfully');
        }else {
            return Redirect::back()->with('err_msg', ''.Config::get('constant.TRY_MESSAGE'));
        }
    }
    public function like_comment($id) {
        try{
            if(Auth::user()) {
                $f = 0;
                $user_id = Auth::user()->id;
                $commentlike = CommentLike::where(array('user_id'=>$user_id,'comment_id'=>$id))->first();
                if($commentlike)
                {
                    if($commentlike->flag == 1) {
                        $deletelike = $commentlike->forceDelete();
                        $likecomment = CommentLike::where(array('comment_id'=>$id,'flag'=>1))->get();
                        $dislikecomment = CommentLike::where(array('comment_id'=>$id,'flag'=>2))->get();
                        if($deletelike) {
                            echo json_encode(array('status' => 0, 'msg' => "Remove comment Liked successfully",'likecount'=>count($likecomment),'dislikecount'=>count($dislikecomment)));
                        }else {
                            echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE'),'likecount'=>count($likecomment),'dislikecount'=>count($dislikecomment)));
                        }
                    } else {
                        $likecomment = CommentLike::where(array('user_id'=>$user_id,'comment_id'=>$id))->update(array('flag'=>1));
                        $likecomment = CommentLike::where(array('comment_id'=>$id,'flag'=>1))->get();
                        $dislikecomment = CommentLike::where(array('comment_id'=>$id,'flag'=>2))->get();
                        if($likecomment) {
                            echo json_encode(array('status' => 1, 'msg' => "comment liked successfully",'likecount'=>count($likecomment),'dislikecount'=>count($dislikecomment)));
                        }else {
                            echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE'),'likecount'=>count($likecomment),'dislikecount'=>count($dislikecomment)));
                        }
                    }
                    
                }else {
                    $likecomment = CommentLike::insert(array('user_id'=>$user_id,'comment_id'=>$id,'flag'=>1));
                    $likecomment = CommentLike::where(array('comment_id'=>$id,'flag'=>1))->get();
                    $dislikecomment = CommentLike::where(array('comment_id'=>$id,'flag'=>2))->get();
                    if($likecomment) {
                        echo json_encode(array('status' => 1, 'msg' => "comment liked successfully",'likecount'=>count($likecomment),'dislikecount'=>count($dislikecomment)));
                    }else {
                        echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE'),'likecount'=>count($likecomment),'dislikecount'=>count($dislikecomment)));
                    }
                }
                
            }else {
               return redirect('/index'); 
            }
        }catch (\exception $e) {
            echo json_encode(array('status' => 0,'msg' => $e->getMessage()));
            //return Redirect::back()->with('err_msg', $e->getMessage());
        }
    }
    public function dislike_comment($id) {
        try{
            if(Auth::user()) {
                $user_id = Auth::user()->id;
                $commentdislike = CommentLike::where(array('user_id'=>$user_id,'comment_id'=>$id))->first();
                if($commentdislike)
                {
                    if($commentdislike->flag == 2) {
                        $deletedislike = $commentdislike->forceDelete();
                        $likecomment = CommentLike::where(array('comment_id'=>$id,'flag'=>1))->get();
                        $dislikecomment = CommentLike::where(array('comment_id'=>$id,'flag'=>2))->get();
                        if($deletedislike) {
                            echo json_encode(array('status' => 0, 'msg' => "Remove comment disiked successfully",'likecount'=>count($likecomment),'dislikecount'=>count($dislikecomment)));
                        }else {
                            echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE'),'likecount'=>count($likecomment),'dislikecount'=>count($dislikecomment)));
                        }
                    }else {
                        $dislikecomment = CommentLike::where(array('user_id'=>$user_id,'comment_id'=>$id))->update(array('flag'=>2));
                        $likecomment = CommentLike::where(array('comment_id'=>$id,'flag'=>1))->get();
                        $dislikecomment = CommentLike::where(array('comment_id'=>$id,'flag'=>2))->get();
                        if($dislikecomment) {
                            echo json_encode(array('status' => 1, 'msg' => "comment disliked successfully",'likecount'=>count($likecomment),'dislikecount'=>count($dislikecomment)));
                        }else {
                            echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE'),'likecount'=>count($likecomment),'dislikecount'=>count($dislikecomment)));
                        }
                    }
                    
                }else {
                    $dislikecomment = CommentLike::insert(array('user_id'=>$user_id,'comment_id'=>$id,'flag'=>2));
                    $likecomment = CommentLike::where(array('comment_id'=>$id,'flag'=>1))->get();
                    $dislikecomment = CommentLike::where(array('comment_id'=>$id,'flag'=>2))->get();
                    if($dislikecomment) {
                        echo json_encode(array('status' => 1, 'msg' => "comment disliked successfully",'likecount'=>count($likecomment),'dislikecount'=>count($dislikecomment)));
                    }else {
                        echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE'),'likecount'=>count($likecomment),'dislikecount'=>count($dislikecomment)));
                    }
                }
            }else {
               return redirect('/index'); 
            }
        }catch (\exception $e) {
             echo json_encode(array('status' => 0,'msg' => $e->getMessage()));
        }
        
    }
    
    public function comment_solution(Request $request) {
        try{
            if(Auth::user()) {
                $comment_id = $request->input('comment_id');
                $user_id = $request->input('user_id');
                if(Comment::where(array('id'=>$comment_id,'is_correct'=>0))->exists()) {
                    $answer = Comment::where('id',$comment_id)->update(array('is_correct'=>1,'is_correct_by_user'=>$user_id));
                    if($answer) {
                        echo json_encode(array('status' => 1, 'msg' => "answer marked successfully"));
                    }else {
                        echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE')));
                    }
                } else {
                    $answer = Comment::where('id',$comment_id)->update(array('is_correct'=>0,'is_correct_by_user'=>0));
                    if($answer) {
                        echo json_encode(array('status' => 0, 'msg' => "answer marked successfully"));
                    }else {
                        echo json_encode(array('status' => 0,'msg' => Config::get('constant.TRY_MESSAGE')));
                    }
                }
                
            }else {
               return redirect('/index'); 
            }
        }catch (\exception $e) {
             echo json_encode(array('status' => 0,'msg' => $e->getMessage()));
        }
    }
}
?>
