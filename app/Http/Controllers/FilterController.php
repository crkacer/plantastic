<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Event;
use App\EventType;
use App\Category;
use Auth;


class FilterController extends Controller
{
    

    public function getByCategory($id) {
        
        $all_events = Event::where('category_id', $id)->get();
        $all_events_types = EventType::all();
        $all_categories = Category::all();
        $types = [];
        $categories = [];
        $pag = [];
        foreach ($all_events_types as $t) {
            array_push($types, ['id' => $t->id, 'text' => $t->name, 'url' => "/event-type/" . $t->id]);
        }
        foreach ($all_categories as $c) {
            array_push($categories, ['id' => $c->id, 'text' => $c->name, 'url' => "/category/" . $c->id]);
        }
        // Push event item to array, 5 items per page
        $temp = [];
        for ($i = 0; $i<count($all_events); $i++) {
            if ($i % 5 == 0) {
                if ($i > 0) array_push($pag, $temp);
                $temp = [];
            }
            $event_type_name = EventType::where('id', $all_events[$i]['event_type_id'])->first()->name;
            $category_name = Category::where('id', $all_events[$i]['category_id'])->first()->name;
            
            array_push($temp, [
                'id' => $all_events[$i]['id'],
                'location' => $all_events[$i]['location'],
                'title' => $all_events[$i]['title'],
                'startdate' => date_format(date_create($all_events[$i]['startdate']),"Y-m-d"),
                'enddate' => date_format(date_create($all_events[$i]['enddate']),"Y-m-d"),
                'starttime' => date_format(date_create($all_events[$i]['starttime']),"H:i"),
                'endtime' => date_format(date_create($all_events[$i]['endtime']),"H:i"),
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
                'price' => $all_events[$i]['price'],
                'event_type_name' => $event_type_name,
                'category_name' => $category_name
            ]);
        }
        array_push($pag,$temp);

        return view('home', [
            'event' => $all_events,
            'pagi' => $pag,
            'event_type' => $types,
            'category' => $categories,
            'user_login' => Auth::user()
        ]);
        
    }
    
    public function getByType($id) {
        
        $all_events = Event::where('event_type_id', $id)->get();
        $all_events_types = EventType::all();
        $all_categories = Category::all();
        $types = [];
        $categories = [];
        $pag = [];
        foreach ($all_events_types as $t) {
            array_push($types, ['id' => $t->id, 'text' => $t->name, 'url' => "/event-type/" . $t->id]);
        }
        foreach ($all_categories as $c) {
            array_push($categories, ['id' => $c->id, 'text' => $c->name, 'url' => "/category/" . $c->id]);
        }
        // Push event item to array, 5 items per page
        $temp = [];
        for ($i = 0; $i<count($all_events); $i++) {
            if ($i % 5 == 0) {
                if ($i > 0) array_push($pag, $temp);
                $temp = [];
            }
            $event_type_name = EventType::where('id', $all_events[$i]['event_type_id'])->first()->name;
            $category_name = Category::where('id', $all_events[$i]['category_id'])->first()->name;
            
            array_push($temp, [
                'id' => $all_events[$i]['id'],
                'location' => $all_events[$i]['location'],
                'title' => $all_events[$i]['title'],
                'startdate' => date_format(date_create($all_events[$i]['startdate']),"Y-m-d"),
                'enddate' => date_format(date_create($all_events[$i]['enddate']),"Y-m-d"),
                'starttime' => date_format(date_create($all_events[$i]['starttime']),"H:i"),
                'endtime' => date_format(date_create($all_events[$i]['endtime']),"H:i"),
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
                'price' => $all_events[$i]['price'],
                'event_type_name' => $event_type_name,
                'category_name' => $category_name
            ]);
        }
        array_push($pag,$temp);

        return view('home', [
            'event' => $all_events,
            'pagi' => $pag,
            'event_type' => $types,
            'category' => $categories,
            'user_login' => Auth::user()
        ]);
        
    }
    
}
