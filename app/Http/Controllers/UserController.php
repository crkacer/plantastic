<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Event;
use App\Attendance;
use Hash;

use Auth;


class UserController extends Controller
{
    public function manageEvent() {
        
        $userID = Auth::user()->id;
        $events = Attendance::where('user_id', $userID)->get();
        // GET events for attended tab
        $attendedEvent = [];
        $temp = [];
        for ($i = 0; $i<count($events); $i++) {
            
            $event = Event::where('id', intval($events[$i]->event_id))->first();
            if ($i % 5 == 0) {
                if ($i > 0) array_push($attendedEvent, $temp);
                $temp = [];
            }
            
            array_push($temp, [
                'id' => $event['id'],
                'location' => $event['location'],
                'title' => $event['title'],
                'startdate' => date_format(date_create($event['startdate']),"Y-m-d"),
                'enddate' => date_format(date_create($event['enddate']),"Y-m-d"),
                'starttime' => date_format(date_create($event['starttime']),"H:i"),
                'endtime' => date_format(date_create($event['endtime']),"H:i"),
                'description' => $event['description'],
                'user_id' => $event['user_id'],
                'organizer_description' => $event['organizer_description'],
                'event_type_id' => $event['event_type_id'],
                'background_photo' => $event['background_photo'],
                'template' => $event['template'],
                'category_id' => $event['category_id'],
                'url' => $event['url'],
                'registered_amount' => $event['registered_amount'],
                'capacity' => $event['capacity'],
                'code' => $event['code'],
                'price' => $event['price']
            ]);
        }
        array_push($attendedEvent, $temp);
        $temp = [];
        // GET events for created tab
        
        $createdEvent = [];
        $events = Event::where('user_id', intval($userID))->get();
        // dump($events);
        // dump($userID);
        foreach ($events as $event) {
            array_push($createdEvent, [
                'id' => $event['id'],
                'location' => $event['location'],
                'title' => $event['title'],
                'startdate' => date_format(date_create($event['startdate']),"Y-m-d"),
                'enddate' => date_format(date_create($event['enddate']),"Y-m-d"),
                'starttime' => date_format(date_create($event['starttime']),"H:i"),
                'endtime' => date_format(date_create($event['endtime']),"H:i"),
                'description' => $event['description'],
                'user_id' => $event['user_id'],
                'organizer_description' => $event['organizer_description'],
                'event_type_id' => $event['event_type_id'],
                'background_photo' => $event['background_photo'],
                'template' => $event['template'],
                'category_id' => $event['category_id'],
                'url' => $event['url'],
                'registered_amount' => $event['registered_amount'],
                'capacity' => $event['capacity'],
                'code' => $event['code'],
                'price' => $event['price']
            ]);
        }
        
        return view('manage-event', [
            'user_login' => Auth::user(),
            'attended' => $attendedEvent,
            'created' => $createdEvent
        ]);
    }

    public function userProfile() {
        $user = Auth::user();
        $user->DOB = date_format(date_create($user->DOB),"Y-m-d");
        return view('profile', [
            'user_login' => $user
        ]);
    }
    
    
    public function postUserProfile() {
        
        $user = User::where('id', intval($_POST['id']))->first();
        
        // $user->photo = $;
         
        $user->email = $_POST['email'];
        $user->password = Hash::make($_POST['password']);
        $user->firstname = $_POST['firstname'];
        $user->lastname = $_POST['lastname'];
        $user->name = $user->firstname ." ". $user->lastname;
        $user->DOB = $_POST['dob'];
        $user->gender = $_POST['gender'];
        $user->social_network = $_POST['socialMedia'];

        if (isset($_FILES['photo'])) {
            // Upload photo

            $target_location = public_path('assets/img/user_photo/') . $user->id;
            $target_file = $target_location . "/" . $_FILES['photo']['name'];
    
            if (!is_dir($target_location)) {
                mkdir($target_location, 0755);
            }
    
            if (file_exists ($target_file)) {
                unlink($target_file);
            }
    
            $ok = move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
    
            if ($ok) {
                $user->profile_picture = "/assets/img/user_photo/" . $user->id . "/" . $_FILES['photo']['name'];
                $user->save();
                return 0;
            }  
            $user->profile_picture = "/assets/img/myAvatar.png";
        }
        $user->save();
        return 0;
    }
}
