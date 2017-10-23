<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;

class LoginController extends Controller
{
    public function index() {
        return View::make('login');
    }

    public function getRegister() {
    	return View::make('register');
    }
}
