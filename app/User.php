<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;
use Auth;

class User extends Authenticatable
{
    use Notifiable;
    use softDeletes;
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function company() {
        return $this->hasOne('App\Company','id','company_id');
    }
    public function group() {
        return $this->hasMany('App\Group','group_owner','id');
    }
    public function groupUser() {
        return $this->hasMany('App\GroupUser','user_id','id')->where('user_id',Auth::user()->id)->where('is_admin',1);
    }
    /*to check user details of a given group */
    public function groupUserDetails()
    {
        return $this->hasOne('App\GroupUser','user_id','id');
    }
    
    public function followers() {
        return $this->hasMany('App\FollowUser','receiver_user_id','id');
    }
    
    public function following() {
        return $this->hasMany('App\FollowUser','sender_user_id','id');
    }
    
}
