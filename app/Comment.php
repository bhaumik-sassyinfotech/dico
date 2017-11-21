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
}
