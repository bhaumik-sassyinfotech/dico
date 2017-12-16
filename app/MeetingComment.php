<?php

namespace App;

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
}
