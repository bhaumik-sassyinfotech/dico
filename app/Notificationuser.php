<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notificationuser extends Model
{
    use softDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'notification_status';
}
