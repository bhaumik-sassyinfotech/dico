<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupUser extends Model {
	//
	use softDeletes;
	protected $fillable = ['user_id', 'group_id', 'company_id', 'is_admin', 'created_at', 'updated_at'];
	protected $dates = ['deleted_at'];

	public function userDetail() {
		return $this->hasOne('App\User', 'id', 'user_id');
	}

	public function followers() {
		return $this->hasMany('App\FollowUser', 'receiver_user_id', 'user_id');
	}

	public function following() {
		return $this->hasMany('App\FollowUser', 'receiver_user_id', 'user_id');
	}

	public function groupDetail() {
		return $this->hasOne('App\group', 'id', 'group_id');
	}
}
