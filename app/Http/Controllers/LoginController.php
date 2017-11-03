<?php

namespace App\Http\Controllers;

use Request;

use App\Http\Requests;
use View;
use Symfony\Component\Console\Input;
use Illuminate\Validation;

class LoginController extends Controller
{
    public function index() {

        if (Request::input('email') && Request::input('password')) {
            $userData = [
                'email' => Request::input('email'),
                'password' => Request::input('password'),
                'type' => 'client'
            ];

            if (Auth::attempt($userData, true)) {
                $login_path = Request::input('login_path') ?: Cookie::get('login_path');
                if (empty($login_path)) {
                    return redirect('/');
                } else {
                    $cookie = Cookie::forget('login_path');
                    return redirect($login_path)->withCookie($cookie);
                }
            } else {
                return redirect('/login/error');
            }
        }

        return view('login', [
        ]);

        return null;
    }

    public function getRegister() {

        if (Request::input('email') && Request::input('password') ) {
            $userData = [
                'fname' => Request::input('first_name'),
                'lname' => Request::input('last_name'),
                'email' => Request::input('email'),
                'password' => Request::input('password')
            ];
            // If user does not exist then allow to create
            if (User::where('email', '=', $userData['username'])->exists()) {
                // handle user already existed

            } else {
                $user = new User;
                $user->name = $userData['fname']." ". $userData['lname'];
                $user->firstname = $userData['fname'];
                $user->lastname = $userData['lname'];
                $user->password = Hash::make($userData['password']);
                $user->email = $userData['username'];
                $user->save();

                $login_path = Request::input('login_path') ?: Cookie::get('login_path');
                if (empty($login_path)) {
                    return redirect('/');
                } else {
                    $cookie = Cookie::forget('login_path');
                    return redirect($login_path)->withCookie($cookie);
                }
            }
        }

    	return View::make('register');
    }

    public function postRegister(Request $request) {

        $data = $request->all();
        $email = $data['email'];
        // validator

        $user = new User();
        $user->profile_image = "/assets/image/default.jpg";
        if (Input::file('photo')) {
            Image::make(Input::file('photo'))->resize(300, 200)->save('image.jpg');
            $currentTime = date('YmdGis');
            $user->profile_image = "/assets/image/" . $data['email'] . "/" . $currentTime;
        }

        $user->name = $data['fname'] + $data['lname'];
        $user->firstname = $data['fname'];
        $user->lastname = $data['lname'];
        $user->DOB = $data['dob'];
        $user->social_network = $data['social_network'];
        $user->gender = $data['gender'];
        $user->password = Hash::make($data['password']);

        $user->save();

        return redirect('/login');
    }
}
