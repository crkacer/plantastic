<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    public function authenticate(Requests $request)
    {
        $data = $request->all();
        $email = $data['email'];
        $password = $data['password'];
        
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            // Authentication passed...
            return redirect()->intended('dashboard');
        } else {
            return redirect('/login');
        }
    }
    
    public function register(Request $request) {
        
        $data = $request->all();
        $email = $data['email'];
        $password = $data['password'];
        
        $user = new User();
        $user->email = $email;
        $user->password = $password;
        $user->save();
        
        return redirect('dashboard');
    }
    
}
