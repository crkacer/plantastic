<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Event;
use App\EventType;
use App\Category;
use App\User;

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
        $all_events = Event::all();
        $all_events_types = EventType::all();
        $all_categories = Category::all();
        $types = [];
        $categories = [];
        foreach ($all_events_types as $t) {
            array_push($types, ['text' => $t->name, 'url' => "event-type/" . $t->name]);
        }
        foreach ($all_categories as $c) {
            array_push($categories, ['text' => $c->name, 'url' => "category/" . $c->name]);
        }
        $this->generateData();
        return view('home', ['event' => $all_events, 'event_type' => $types, 'category' => $categories]);
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
