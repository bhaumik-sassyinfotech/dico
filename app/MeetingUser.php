<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

{
    //
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
