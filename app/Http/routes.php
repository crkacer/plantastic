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

Route::any('login/{error?}', 'LoginController@index');
Route::get('logout', 'LoginController@logout');

Route::get('event/{id?}', 'EventController@getIndex');

Route::get('view-event/{id?}', 'EventController@getIndexEvent');

Route::get('event/dashboard/{id?}', 'EventController@getDashboard');

Route::get('register', 'LoginController@getRegister');

Route::get('home', 'HomeController@index');

Route::any('/password/reset', 'LoginController@resetPassword');

Route::post('search', 'SearchController@index');
Route::post('check-email', 'LoginController@checkEmail');

// {'email': "gab@gmail.com"} => true, false
// User routes


Route::group(['middleware' => ['web', 'auth.user']], function () {

    Route::get('user/manage-event', ['as' => 'get-event-manage', 'uses' => 'UserController@manageEvent']);
    Route::get('user/profile', ['as' => 'get-user-profile', 'uses' => 'UserController@userProfile']);
    Route::get('event/dashboard/{id?}', ['as' => 'get-event-dashboard', 'uses' => 'EventController@getDashboard']);
    Route::any('event/create', ['as' => 'get-create-event', 'uses' => 'EventController@createEvent']);

});

// Dummy routes

Route::get('generate', "HomeController@generateData");