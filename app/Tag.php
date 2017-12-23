<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    //
    use softDeletes;
    protected $dates = ['deleted_at'];
    
    public function tagPosts()
    {
        return $this->hasMany('App\PostTag','tag_id','id');
    }
}
