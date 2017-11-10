<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Event;
use App\EventType;
use App\Category;
use Auth;


class EventController extends Controller
{
    public function getIndex($id) {
        $event = Event::where('id', $id)->first();

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


        $similarEvent = Event::where('category_id', $event->category_id)->take(20)->get();
        // dump($event);

        // Push event item to array, 5 items per page
        $temp = [];
        $pag = [];
        for ($i = 0; $i<count($similarEvent); $i++) {
            if ($i % 3 == 0) {
                if ($i > 0) array_push($pag, $temp);
                $temp = [];
            }
            array_push($temp, [
                'id' => $similarEvent[$i]['id'],
                'location' => $similarEvent[$i]['location'],
                'title' => $similarEvent[$i]['title'],
                'startdate' => $similarEvent[$i]['startdate'],
                'endate' => $similarEvent[$i]['endate'],
                'starttime' => $similarEvent[$i]['starttime'],
                'endtime' => $similarEvent[$i]['endtime'],
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
                'price' => $similarEvent[$i]['price']
            ]);
        }

        return view('view-event', [
            'event' => $event,
            'suggestion' => $similarEvent,
            'pagi' => $pag,
            'all_type' => $types,
            'all_cat' => $categories,
            'user_login' => Auth::user()

        ]);
    }

    public function getIndexEvent($id) {
        return view('view-event2', [
            'user_login' => Auth::user()
        ]);
    }

    public function getDashboard($id) {

        $event = Event::where('id', $id)->first();

        return view('event.dashboard', [
            'user_login' => Auth::user(),
            'event' => $event
        ]);
    }

    public function getEventCode($id) {
        // A-Z 65-90 0-9

        $allowedChar = [];
        $randomStr = "";
        for ($i = 0; $i<10; $i++) {
            array_push($allowedChar, $i);
        }
        for ($i = 65; $i<91; $i++) {
            array_push($allowedChar, $i);
        }
        for ($i = 1; $i<5; $i++) {
            $temp = rand(0,count($allowedChar)-1);
            if ($temp > 64) $randomStr += chr($temp);
            else $randomStr += $temp;
        }
        $randomStr += $id;
        return $randomStr;
    }

    public function createEvent() {

        return view('create-event', [
            'user_login' => Auth::user()
        ]);
    }
}
