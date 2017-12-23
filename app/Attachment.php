<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    //
    protected $table = 'attachments';
    use SoftDeletes;
    
    public function attachmentUser()
    {
        return $this->hasOne('App\User' , 'id' , 'user_id');
    }
}
