<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingAttachment extends Model
{
    //
    protected $table = 'meeting_attachments';
    use SoftDeletes;
    
    public function attachmentUser()
    {
        return $this->hasOne('App\User','id','user_id');
    }
}
