<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    public function commentDisLike()
    {
        return $this->hasMany('App\CommentLike','comment_id','id')->where('flag',2);
    }
    public function commentUserLike()
    {
        return $this->hasOne('App\CommentLike','comment_id','id')->where('flag',1);
    }
    public function commentUserDisLike()
    {
        return $this->hasOne('App\CommentLike','comment_id','id')->where('flag',2);
    }
    public function commentReply()
    {
        return $this->hasMany('App\CommentReply','comment_id','id');
    }
}
