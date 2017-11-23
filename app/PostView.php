<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    //
//    use softDeletes;
    protected $fillable = ['created_at','updated_at','visitor_ip','user_id','post_id'];
}
