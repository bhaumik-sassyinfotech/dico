<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Point extends Model
{
    //
    protected $table = 'point_master';
    use SoftDeletes;
}
