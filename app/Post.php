<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    //
    use SoftDeletes;
    public function postUser()
    {
        return $this->hasOne('App\User','id','user_id');
    }
    public function postLike()
    {
        return $this->hasMany('App\PostLike','post_id','id');
    }
    public function postUserLike()
    {
        return $this->hasOne('App\PostLike','post_id','id');
    }
    public function postAttachment()
    {
        return $this->hasOne('App\Attachment','type_id','id');
    }
    public function postComment()
    {
        return $this->hasMany('App\Comment','post_id','id');
    }
}
