<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Event;

class SearchController extends Controller
{
    public function index() {

        $search = Request::input('keyword');
        $events = Event::all();
        foreach ($events as $e) {
            if (str_contains($e['location'], $search) || str_contains($e['title'], $search) || str_contains($e['location'], ))
        }
    }
}
