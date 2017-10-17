<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class EventController extends Controller
{
	public function getIndex($id) {
		return view('view-event');
	}

	public function getIndexEvent($id) {
		return view('view-event2');
	}
}
