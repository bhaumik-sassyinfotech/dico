<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public function meetingUsers()
    {
        return $this->hasMany('App\MeetingUser','meeting_id','id');
    }
}
