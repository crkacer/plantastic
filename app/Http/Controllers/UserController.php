<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;


class UserController extends Controller
{
    public function manageEvent() {
        return view('manage-event', [
            'user_login' => Auth::user()
        ]);
    }

    public function userProfile() {
        return view('profile', [
            'user_login' => Auth::user()
        ]);
    }
}
