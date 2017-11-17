<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;

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
        return $this->hasMany('App\Company');
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
        return $this->hasMany('App\FollowUser','receiver_user_id','id');
    }
}
