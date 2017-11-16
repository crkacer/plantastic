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
        
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        

        $user = new User();
        
        $user->profile_picture = "/assets/img/myAvatar.png";
        
        $location = "/assets/img/myAvatar.png";
        //  or File::makeDirectory(storage_path('app/blogpost/' . $postId));
//         if ($_FILES['photo'] != null) {
            
            
//             $currentTime = date('YmdGis');
            
//             // $location = "/home/ubuntu/workspace/public/assets/img/" . $fname.$lname . "/" . $currentTime. "/avatar.png";
//             // if (!file_exists ( "/home/ubuntu/workspace/public/assets/img/" . $fname.$lname . "/" . $currentTime)) {
//             //     mkdir("/home/ubuntu/workspace/public/assets/img/" . $fname.$lname . "/" . $currentTime);
//             // }
//             // Image::make($_FILES['photo']['tmp_name'])->resize(100, 100)->save($location);
//             // move_uploaded_file($_FILES['photo']['tmp_name'], $location);
            
//             $image = $_FILES['photo'];
//             $imgpath = $_FILES['photo']['name'];
//             $ext = pathinfo($imgpath, PATHINFO_EXTENSION);
//          $filename  = $fname.$lname . "/" . $currentTime. "/avatar.".$ext;
//          $path = public_path('assets/img/' . $filename);
            
// //           File::exists(public_path('assets/img/').$fname.$lname . "/" . $currentTime);
//             if (!is_dir(public_path('assets/img/').$fname.$lname . "/" . $currentTime)) {
//                 mkdir(public_path('assets/img/').$fname.$lname . "/" . $currentTime, 0755);
//             }
//             $fp = fopen($path, "w");
//             fwrite($fp, file_get_contents($_FILES['photo']['tmp_name']), "w");
            
            
// //           Image::make($_FILES['photo']['tmp_name'])->resize(100, 100)->save($path);
            
//             $user->profile_picture = $path;
//         }

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
            

            Mail::send('email.reset-password', [
                'email' => $user->email,
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
