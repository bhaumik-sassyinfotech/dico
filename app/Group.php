<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model {
	//
	use SoftDeletes;

	public function groupUsers() {
		return $this->hasMany('App\GroupUser', 'group_id', 'id');
	}
	public function groupUsersCount() {
//        return $this->hasMany('App\GroupUser','group_id','id');
		return $this->hasOne('App\GroupUser')
			->selectRaw('group_id, count(*) as cnt')
			->groupBy('group_id');
	}
	public function groupPosts() {
		// return $this->hasOne('App\Post', 'group_id', 'id');

		return $this->hasMany('App\Post')
			->where('group_id', 'like', "%,id,%");
	}
}
