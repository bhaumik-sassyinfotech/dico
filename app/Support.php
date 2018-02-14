<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Support extends Model
{
    use softDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'support';
    protected $fillable = ['issue','description'];
    
}
