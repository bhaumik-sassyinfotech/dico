<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserActivity extends Model {
	//
	use SoftDeletes;

	public function user_detail() {
		return $this->hasOne('App\User', 'id', 'user_id');
	}
}
