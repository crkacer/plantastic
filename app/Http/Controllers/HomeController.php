<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Event;
use App\EventType;
use App\Category;
use App\User;
use Image;
use Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        dump(Auth::user());
        $all_events = Event::all();
        $all_events_types = EventType::all();
        $all_categories = Category::all();
        $types = [];
        $categories = [];
        $pag = [];
        foreach ($all_events_types as $t) {
            array_push($types, ['text' => $t->name, 'url' => "event-type/" . $t->name]);
        }
        foreach ($all_categories as $c) {
            array_push($categories, ['text' => $c->name, 'url' => "category/" . $c->name]);
        }
        // Push event item to array, 5 items per page
        $temp = [];
        for ($i = 0; $i<count($all_events); $i++) {
            if ($i % 5 == 0) {
                if ($i > 0) array_push($pag, $temp);
                $temp = [];
            }
            array_push($temp, [
                'id' => $all_events[$i]['id'],
                'location' => $all_events[$i]['location'],
                'title' => $all_events[$i]['title'],
                'startdate' => $all_events[$i]['startdate'],
                'endate' => $all_events[$i]['endate'],
                'starttime' => $all_events[$i]['starttime'],
                'endtime' => $all_events[$i]['endtime'],
                'description' => $all_events[$i]['description'],
                'user_id' => $all_events[$i]['user_id'],
                'organizer_description' => $all_events[$i]['organizer_description'],
                'event_type_id' => $all_events[$i]['event_type_id'],
                'background_photo' => $all_events[$i]['background_photo'],
                'template' => $all_events[$i]['template'],
                'category_id' => $all_events[$i]['category_id'],
                'url' => $all_events[$i]['url'],
                'registered_amount' => $all_events[$i]['registered_amount'],
                'capacity' => $all_events[$i]['capacity'],
                'code' => $all_events[$i]['code'],
                'price' => $all_events[$i]['price']
            ]);
        }

        return view('home', [
            'event' => $all_events,
            'pagi' => $pag,
            'event_type' => $types,
            'category' => $categories,
            'user_login' => Auth::user()
        ]);
    }

    public function generateData () {

        $event = new Event();
        $event->location = "12 Bloor Street";
        $event->title = "Game Meetings";
        $event->startdate = "";
        $event->enddate = "";
        $event->starttime = "";
        $event->endtime = "";
        $event->description = "";
        $event->user_id = "";
        $event->organizer_description = "";
        $event->event_type_id = "";
        $event->background_photo = "";
        $event->template = "";
        $event->category_id = "";
        $event->url = "";
        $event->registered_amount = "";
        $event->capacity = "";
        $event->code = "";
        $event->price = "";
        $event->save();
    }
}
