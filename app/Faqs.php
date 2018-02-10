<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faqs extends Model
{
    //
    use softDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'faqs';
    protected $fillable = ['question','answer'];
    
}
