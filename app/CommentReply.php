<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentReply extends Model
{
    //
    use SoftDeletes;
    public function commentReplyUser()
    {
        return $this->hasOne('App\User','id','user_id');
    }
}
