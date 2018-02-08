<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowUser extends Model
{
    //
    use SoftDeletes;
    
    public function followUser() {
        return $this->hasOne('App\User','id','sender_user_id');
    }
    
    public function followingUser() {
        return $this->hasOne('App\User','id','receiver_user_id');
    }
    
}
