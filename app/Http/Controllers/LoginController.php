<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests;
use App\User;
use View;
use Symfony\Component\Console\Input;
use Illuminate\Validation;
use Auth;
use Cookie;
use Session;
use Hash;
use Image;
use Mail;


class LoginController extends Controller
{
    public function index() {
        
        $error = "correct";
        // handle user logged in but manually go through register route
        if (Auth::check()) return redirect('/home');
        if (\Request::input('email') && \Request::input('password')) {
            $userData = [
                'email' => \Request::input('email'),
                'password' => \Request::input('password')
            ];

            if (Auth::attempt($userData, true)) {
                $login_path = \Request::input('login_path') ?: Cookie::get('login_path');
                if (empty($login_path)) {
                    return redirect('/');
                } else {
                    $cookie = Cookie::forget('login_path');
                    return redirect($login_path)->withCookie($cookie);
                }
            } else {
                $error = "Username or Password is incorrect";
            }
        }

        return view('login', [
            'user_login' => Auth::user(),
            'error' => json_encode($error)
        ]);

        return null;
    }

    public function getRegister() {

        if (\Request::input('email') && \Request::input('password') ) {
            $userData = [
                'fname' => \Request::input('first_name'),
                'lname' => \Request::input('last_name'),
                'email' => \Request::input('email'),
                'password' => \Request::input('password')
            ];
            // If user does not exist then allow to create
            if (User::where('email', '=', $userData['username'])->exists()) {
                // handle user already existed

            } else {
                // if not then processing register
                $user = new User;
                $user->name = $userData['fname']." ". $userData['lname'];
                $user->firstname = $userData['fname'];
                $user->lastname = $userData['lname'];
                $user->password = Hash::make($userData['password']);
                $user->email = $userData['username'];
                $user->save();

                $login_path = \Request::input('login_path') ?: Cookie::get('login_path');
                if (empty($login_path)) {
                    return redirect('/');
                } else {
                    $cookie = Cookie::forget('login_path');
                    return redirect($login_path)->withCookie($cookie);
                }
            }
        }

        return View::make('register', [
            'user_login' => Auth::user()
        ]);
    }

    public function postRegister() {
        // handle register new user
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        

        $user = new User();
        
        $user->profile_picture = "/assets/img/myAvatar.png";
        
        $location = "/assets/img/myAvatar.png";

        $user->name = $fname;
        $user->firstname = $fname;
        $user->lastname = $lname;
        $user->DOB = $dob;
        $user->gender = $gender;
        $user->social_network = " ";
        $user->email = $email;
        $user->password = Hash::make($password);
        
        if ($user->save()) {
            return 0;
        }
        return 1;
        
        
    }

    // handle ajax request to check email existence
    public function checkEmail(Request $request) {
        
        $data = $request->all();
        $email = $data['email'];
        $user = User::where('email', $email)->first();
        // return $user;
        if ($user == null) {
            return 0;
        }
        return 1;

    }

    public function resetPassword() {

        $error = null;

        return view('auth.passwords.reset', [
            'user_login' => Auth::user(),
            'error' => $error
        ]);
    }

    public function postEmailReset(Request $request) {

        $data = $request->all();
        $email = $data['email'];
        
        $user = User::where('email', $email)->first();
        if ($user != null) {
            
            // generate random password for user
            $code = "";
            $allowedChar = [];
            for ($i = 0; $i<10; $i++) {
                array_push($allowedChar, $i);
            }
            for ($i = 65; $i<91; $i++) {
                array_push($allowedChar, $i);
            }
            for ($i = 1; $i<10; $i++) {
                $temp = rand(0,count($allowedChar)-1);
                if ($allowedChar[$temp] > 64) $code .= chr($allowedChar[$temp]);
                else $code .= $allowedChar[$temp];
            }

            $user->password = Hash::make($code);
            
            // send email to user with new password
            Mail::send('email.reset-password', [
                'user' => $user,
                'password' => $code
            ], function ($message) use ($user)
                {

                    $message->from('plantastic.tech5upport@gmail.com', 'Reset password confirmation:');

                    $message->to($user->email);

                });
            $user->save();
            return 0;
        }
        return 1;

    }



    public function logout() {
        Auth::logout();
        Session::flush();
        return redirect("/login");
    }

}
