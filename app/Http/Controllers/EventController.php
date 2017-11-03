<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Event;

class EventController extends Controller
{
	public function getIndex($id) {
	    $event = Event::where('id', $id)->get();

		return view('view-event', [
		    'event' => $event
        ]);
	}

	public function getIndexEvent($id) {
	    $event = Event::where('id', $id)->get();

		return view('view-event2', [
		    'event' => $event
        ]);
	}

	public function getDashboard($id) {

	    return view('event.dashboard');
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
}
