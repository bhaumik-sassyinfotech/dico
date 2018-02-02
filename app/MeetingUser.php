<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
class MeetingUser extends Model
{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['meeting_id','user_id', 'group_id' ,'is_admin','created_at','updated_at'];
    
    public function UserDetail() {
        if (Auth::user()->role_id > 1) {
                return $this->hasOne('App\User', 'id', 'user_id')->where('company_id', Auth::user()->company_id);
        } else {
                return $this->hasOne('App\User', 'id', 'user_id');
        }

    }
}
