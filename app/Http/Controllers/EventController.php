<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Event;
use App\EventType;
use App\Category;
use App\Attendance;
use App\User;
use Auth;
use DB;

class EventController extends Controller
{
    public function getIndex($id) {
        $event = Event::where('id', $id)->first();
        $event_type_name = EventType::where('id', $event['event_type_id'])->first()->name;
        $category_name = Category::where('id', $event['category_id'])->first()->name;
        $event->category_name = $category_name;
        $event->event_type_name = $event_type_name;
        // dump($event->enddate);
        $selectedEvent = $event;
        
        $selectedEvent->startdate = date_format(date_create($event->startdate),"Y-m-d");
        $selectedEvent->enddate = date_format(date_create($event->enddate),"Y-m-d");
        $selectedEvent->starttime = date_format(date_create($event->starttime),"H:i");
        $selectedEvent->endtime = date_format(date_create($event->endtime),"H:i");
        
        
        $types = [];
        $categories = [];
        $all_events_types = EventType::all();
        $all_categories = Category::all();

        foreach ($all_events_types as $t) {
            array_push($types, ['text' => $t->name, 'url' => "event-type/" . $t->name]);
        }
        foreach ($all_categories as $c) {
            array_push($categories, ['text' => $c->name, 'url' => "category/" . $c->name]);
        }


        
        $similarEvent = [];
        $similarEvent = Event::where('category_id', $event->category_id)->take(20)->get();
        // dump($event);

        // Push event item to array, 3 items per page
        $temp = [];
        $pag = [];
        for ($i = 0; $i<count($similarEvent); $i++) {
            if ($i % 3 == 0) {
                if ($i > 0) array_push($pag, $temp);
                $temp = [];
            }
            $event_type_name = EventType::where('id', $similarEvent[$i]['event_type_id'])->first()->name;
            $category_name = Category::where('id', $similarEvent[$i]['category_id'])->first()->name;
            
            array_push($temp, [
                'id' => $similarEvent[$i]['id'],
                'location' => $similarEvent[$i]['location'],
                'title' => $similarEvent[$i]['title'],
                'startdate' => date_format(date_create($similarEvent[$i]['startdate']),"Y-m-d"),
                'enddate' => date_format(date_create($similarEvent[$i]['enddate']),"Y-m-d"),
                'starttime' => date_format(date_create($similarEvent[$i]['starttime']),"H:i"),
                'endtime' => date_format(date_create($similarEvent[$i]['endtime']),"H:i"),
                'description' => $similarEvent[$i]['description'],
                'user_id' => $similarEvent[$i]['user_id'],
                'organizer_description' => $similarEvent[$i]['organizer_description'],
                'event_type_id' => $similarEvent[$i]['event_type_id'],
                'background_photo' => $similarEvent[$i]['background_photo'],
                'template' => $similarEvent[$i]['template'],
                'category_id' => $similarEvent[$i]['category_id'],
                'url' => $similarEvent[$i]['url'],
                'registered_amount' => $similarEvent[$i]['registered_amount'],
                'capacity' => $similarEvent[$i]['capacity'],
                'code' => $similarEvent[$i]['code'],
                'price' => $similarEvent[$i]['price'],
                'event_type_name' => $event_type_name,
                'category_name' => $category_name
            ]);
        }
        
        array_push($pag,$temp);
        
        // Handle event attendance
        $attended = 0;
        if (Auth::check()) {
            $user = Auth::user();
            $attend = Attendance::where([['event_id', $id], ['user_id', $user->id]])->first();
            if ($attend != null) {
                $attended = 1;
            }
        }
        
        $template = 'view-event';
        if ($event->template == "B") $template = 'view-event2';
        
        return view($template, [
            'event' => $selectedEvent,
            'attended' => $attended,
            'suggestion' => $similarEvent,
            'pagi' => $pag,
            'all_type' => $types,
            'all_cat' => $categories,
            'user_login' => Auth::user()

        ]);
    }

    public function getIndexEvent($id) {
        
        $attended = false;
        if (Auth::check()) {
            $user = Auth::user();
            $attend = Attendance::where([['event_id', $id], ['user_id', $user->id]])->first();
            if ($attend != null) {
                $attended = true;
            }
        }
        
        return view('view-event2', [
            'user_login' => Auth::user(),
            'attended' => $attended
        ]);
    }
    
    public function attendEvent(Request $request) {
        $data = $request->all();
        $user = $data['user'];
        $event = $data['event'];
        
        $e = Event::where('id', intval($event))->first();
        if ($e->registered_amount < $e->capacity) {
            $e->registered_amount ++;
            $e->save();
            $attend = new Attendance();
            $attend->user_id = intval($user);
            $attend->event_id = intval($event);
            $attend->save();
            
            return 1;
        }
        return 0;
    }

    public function getDashboard($id) {
        
        $all_events_types = EventType::all();
        $all_categories = Category::all();
        $types = [];
        $categories = [];
        
        foreach ($all_events_types as $t) {
            array_push($types, ['id' => $t->id, 'text' => $t->name, 'url' => "/event-type/" . $t->id]);
        }
        foreach ($all_categories as $c) {
            array_push($categories, ['id' => $c->id, 'text' => $c->name, 'url' => "/category/" . $c->id]);
        }
        
        
        $event = Event::where('id', $id)->first();
        if ($event != null) {
            $event->startdate = date_format(date_create($event->startdate),"Y-m-d");
            $event->enddate = date_format(date_create($event->enddate),"Y-m-d");
            $event->starttime = date_format(date_create($event->starttime),"H:i");
            $event->endtime = date_format(date_create($event->endtime),"H:i");
        }
        $attend = Attendance::where('event_id', $id)->get();
        $peopleAttended = [];
        foreach ($attend as $at) {
            $person = User::where('id', $at->user_id)->first();
            $person->DOB = date_format(date_create($person->DOB), "Y-m-d");
            array_push($peopleAttended, $person);
        }
        
        $category = Category::where('id', $event->category_id)->first()->name;
        $event_type = EventType::where('id', $event->event_type_id)->first()->name;

        return view('event.dashboard', [
            'user_login' => Auth::user(),
            'event' => $event,
            'category' => $category,
            'event_type' => $event_type,
            'people_attend' => $peopleAttended,
            'all_categories' => $categories,
            'all_types' => $types
        ]);
    }


    public function createEvent() {
        
        $all_events_types = EventType::all();
        $all_categories = Category::all();
        $types = [];
        $categories = [];
        
        foreach ($all_events_types as $t) {
            array_push($types, ['id' => $t->id, 'text' => $t->name]);
        }
        foreach ($all_categories as $c) {
            array_push($categories, ['id' => $c->id, 'text' => $c->name]);
        }
        
        
        return view('create-event', [
            'user_login' => Auth::user(),
            'all_categories' => $categories,
            'all_types' => $types
        ]);
    }
    
    public function postCreateEvent() {
        
        $photo = $_FILES['photo'];
 
       
        // generate unique code
        $code = "0000000";
        
        if ($_POST['uniqueCode'] == "Y") {
            $nextID = DB::table('events')->max('id') + 1;
            $code = "";
            $allowedChar = [];
            for ($i = 0; $i<10; $i++) {
                array_push($allowedChar, $i);
            }
            for ($i = 65; $i<91; $i++) {
                array_push($allowedChar, $i);
            }
            for ($i = 1; $i<5; $i++) {
                $temp = rand(0,count($allowedChar)-1);
                if ($allowedChar[$temp] > 64) $code .= chr($allowedChar[$temp]);
                else $code .= $allowedChar[$temp];
            }
            $code .= $nextID;
        }  
        $event = new Event();
        $event->location = $_POST['location'];
        $event->title = $_POST['title'];
        $event->startdate = $_POST['startdate'];
        $event->enddate = $_POST['enddate'];
        $event->starttime = "0000-00-00 " . $_POST['starttime'];
        $event->endtime = "0000-00-00 " . $_POST['endtime'];
        $event->description = $_POST['description'];
        $event->user_id = Auth::user()->id;
        $event->organizer_description = $_POST['organizerDescription'];
        $event->event_type_id = intval($_POST['type']);
        $event->category_id = intval($_POST['category']);
        $event->url = "";
        $event->template = $_POST['layoutID'];
        $event->registered_amount = 0;
        $event->capacity = intval($_POST['capacity']);
        $event->code = $code;
        $event->price = $_POST['price'];
        $event->background_photo = "/assets/img/blur.jpg";
        $event->save();
        
        
        return 0;
    }
    
    
    public function deleteEvent(Request $request) {
        
        $data = $request->all();
        $event = Event::where('id', intval($data['id']))->first();
        if ($event == null) {
            return 1;
        }
        // delete attendances of that event
        $attendances = Attendance::where('event_id', intval($event->id))->get();
        foreach ($attendances as $at) {
            $at->delete();
        }
        // delete that event
        $event->delete();
        
        return 0;
    }
}
