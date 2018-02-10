<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contactus extends Model
{
    use softDeletes;
    protected $table = 'contact_us';
    protected $fillable = ['name','email','mobile','message'];
    
}
