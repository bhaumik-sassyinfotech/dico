<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Packages extends Model
{
    //
    use softDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'packages';
    protected $fillable = ['name','amount','total_user'];
    
}
