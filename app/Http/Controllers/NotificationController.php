<?php

namespace App\Http\Controllers;
use Notificationuser;
use Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() {
        
    }
    public function notificationOnOff(Request $request) {
        $notificationuser = new Notificationuser;
        $notificationuser->user_id = Auth::user()->id;
        $notificationuser->user_id = Auth::user()->id;
    }

}
