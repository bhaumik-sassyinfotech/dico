<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostTag extends Model
{
    //
    use SoftDeletes;
    public function tag()
    {
        return $this->hasOne('App\Tag' , 'id' , 'tag_id');
    }
}
