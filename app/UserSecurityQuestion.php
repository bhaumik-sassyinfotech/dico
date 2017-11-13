<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSecurityQuestion extends Model
{
    //
    use softDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['question_id','answer','user_id'];
}
