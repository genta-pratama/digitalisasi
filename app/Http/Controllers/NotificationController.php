<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function readAll()
    {
        auth()->user()?->unreadNotifications->markAsRead();
        return back();
    }
}