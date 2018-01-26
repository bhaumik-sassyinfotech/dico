<?php

namespace App;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingComment extends Model
{
    //
    use SoftDeletes;
    
    public function commentUser()
    {
        return $this->hasOne('App\User','id','user_id');
    }

    public function commentAttachment()
    {
        return $this->hasOne('App\MeetingAttachment','type_id','id')->where('type', 2);
    }
  
    public function commentReply()
    {
        return $this->hasMany('App\MeetingCommentReply','comment_id','id')->orderBy('id','desc');
    }
     public function commentLike()
    {
        return $this->hasMany('App\MeetingCommentLikes','meeting_comment_id','id')->where('flag',1);
    }
    public function commentDisLike()
    {
        return $this->hasMany('App\MeetingCommentLikes','meeting_comment_id','id')->where('flag',2);
    }
    public function commentUserLike()
    {
        return $this->hasOne('App\MeetingCommentLikes','meeting_comment_id','id')->where('flag',1)->where('user_id',Auth::user()->id);
    }
    public function commentUserDisLike()
    {
        return $this->hasOne('App\MeetingCommentLikes','meeting_comment_id','id')->where('flag',2)->where('user_id',Auth::user()->id);
    }
}
