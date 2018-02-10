<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blocks extends Model
{
    //
    use softDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'blocks';
    protected $fillable = ['slug_name','title','description'];
    
}
