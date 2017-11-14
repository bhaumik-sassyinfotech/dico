<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTagPost extends Model
{
    //
    use softDeletes;
    protected $dates = ['deleted_at'];
}
