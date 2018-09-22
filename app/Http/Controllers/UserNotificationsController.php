<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UserNotificationsController extends Controller
{

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        return auth()->user()->unreadNotifications;
    }
    
    public function destroy(User $user, $notificationId){

        // dd($user);

        auth()->user()->notifications()->findorFail($notificationId)->markAsRead();
    }
}
