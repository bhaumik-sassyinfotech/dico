<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    //
    use softDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'feedback';
    protected $fillable = ['subject','description'];
    
}
