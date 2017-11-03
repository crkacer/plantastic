<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Guest routes
Route::get('/', 'HomeController@index');

Route::get('login/{error?}', 'LoginController@index');

Route::get('event/{id?}', 'EventController@getIndex');

Route::get('view-event/{id?}', 'EventController@getIndexEvent');


Route::get('register', 'LoginController@getRegister');

Route::get('home', 'HomeController@index');




// User routes


Route::group(['middleware' => ['web', 'auth.user']], function () {

    Route::get('user/manage-event', ['as' => 'get-event-manage', 'uses' => 'UserController@manageEvent']);
    Route::get('event/dashboard/{id?}', ['as' => 'get-event-dashboard', 'uses' => 'EventController@getDashboard']);

});

// Dummy routes

Route::get('generate', "HomeController@generateData");