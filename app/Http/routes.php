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

Route::get('/', 'HomeController@index');
});

Route::get('login', 'LoginController@index');

Route::get('event/{id?}', 'EventController@getIndex');
Route::get('view-event/{id?}', 'EventController@getIndexEvent');

Route::get('register', 'LoginController@getRegister');

Route::get('home', 'HomeController@index');

Route::get('user/manage-event', 'UserController@manageEvent');
