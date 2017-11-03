<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Event;
use App\EventType;
use App\Category;

class SearchController extends Controller
{
    public function index() {

        $search = Request::input('keyword');
        $events = Event::all();
        $arrEvent = [];
        foreach ($events as $e) {
            $category = Category::where('id', $e['category_id'])->first()->name;
            $event_type = EventType::where('id', $e['event_type_id'])->first()->name;
            if (str_contains($e['location'], $search) || str_contains($e['title'], $search)
                || str_contains($e['description'], $search) || str_contains($e['organizer_description'], $search)
                || str_contains($category, $search) || str_contains($event_type, $search) || str_contains($e['code'], $search)
            ) {
                array_push($arrEvent, [
                    'id' => $e['id'],
                    'location' => $e['location'],
                    'title' => $e['title'],
                    'startdate' => $e['startdate'],
                    'endate' => $e['endate'],
                    'starttime' => $e['starttime'],
                    'endtime' => $e['endtime'],
                    'description' => $e['description'],
                    'user_id' => $e['user_id'],
                    'organizer_description' => $e['organizer_description'],
                    'event_type_id' => $e['event_type_id'],
                    'background_photo' => $e['background_photo'],
                    'template' => $e['template'],
                    'category_id' => $e['category_id'],
                    'url' => $e['url'],
                    'registered_amount' => $e['registered_amount'],
                    'capacity' => $e['capacity'],
                    'code' => $e['code'],
                    'price' => $e['price']
                ]);
            }
        }

        return view('search', [
            'event' => $arrEvent
        ]);
    }
}
