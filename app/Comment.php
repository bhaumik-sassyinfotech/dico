<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Comment extends Model
{
    //
    use SoftDeletes;
    
    public function commentUser()
    {
        return $this->hasOne('App\User','id','user_id');
    }

    public function commentAttachment()
    {
        return $this->hasOne('App\Attachment','type_id','id')->where('type', 2);
    }
    public function commentLike()
    {
        return $this->hasMany('App\CommentLike','comment_id','id')->where('flag',1);
    }
    /*public function commentLikeCount()
    {
        return $this->hasMany('App\CommentLike','comment_id','id')->where('flag',1)->count();
    }*/
    public function commentDisLike()
    {
        return $this->hasMany('App\CommentLike','comment_id','id')->where('flag',2);
    }
    public function commentUserLike()
    {
        return $this->hasOne('App\CommentLike','comment_id','id')->where('flag',1)->where('user_id',Auth::user()->id);
    }
    public function commentUserDisLike()
    {
        return $this->hasOne('App\CommentLike','comment_id','id')->where('flag',2)->where('user_id',Auth::user()->id);
    }
    public function commentReply()
    {
        return $this->hasMany('App\CommentReply','comment_id','id')->orderBy('id','desc');
    }
    public function commentFlagged()
    {
        return $this->hasOne('App\CommentFlag' , 'comment_id' , 'id');
    }
    public function postTagUsers()
    {
        return $this->hasMany('App\PostTagUser' , 'comment_id' , 'id');
    }
}
