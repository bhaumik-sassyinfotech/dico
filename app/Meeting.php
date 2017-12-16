<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Meeting extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    public function meetingUsers()
    {
        return $this->hasMany('App\MeetingUser','meeting_id','id');
    }
    
    public function meetingCreator()
    {
        return $this->hasOne('App\User' , 'id' , 'created_by');
    }
    
    public function meetingUser()
    {
        return $this->hasOne('App\User' , 'id' , 'user_id')->where('company_id' , Auth::user()->company_id);
    }
    
    public function meetingAttachment()
    {
        return $this->hasOne('App\MeetingAttachment' , 'type_id' , 'id')->where('type' , 1);
    }
    
    public function meetingComment()
    {
        return $this->hasMany('App\MeetingComment' , 'meeting_id' , 'id')->orderBy('id' , 'desc');
    }
    
}
