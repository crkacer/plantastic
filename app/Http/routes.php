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

// Guest routes (eg. routes without login requirement)
Route::get('/', 'HomeController@index');

Route::any('login/{error?}', 'LoginController@index');
Route::get('logout', 'LoginController@logout');



Route::get('view-event/{id?}', 'EventController@getIndexEvent');

Route::get('event/dashboard/{id?}', 'EventController@getDashboard');

Route::get('register', 'LoginController@getRegister');

Route::get('home', 'HomeController@index');

Route::get('/password/reset', 'LoginController@resetPassword');
Route::post('/password/check-email', 'LoginController@postEmailReset');


Route::post('search', 'SearchController@index');
Route::post('check-email', 'LoginController@checkEmail');
Route::post('register', 'LoginController@postRegister');

// User routes

// routes protected with auth.user middleware (eg. required log in)
Route::group(['middleware' => ['web', 'auth.user']], function () {

    // user routes
    Route::get('user/manage-event', ['as' => 'get-event-manage', 'uses' => 'UserController@manageEvent']);
    Route::get('user/profile', ['as' => 'get-user-profile', 'uses' => 'UserController@userProfile']);
    Route::post('user/profile', ['as' => 'post-user-profile', 'uses' => 'UserController@postUserProfile']);
    
    // event routes
    Route::get('event/dashboard/{id?}', ['as' => 'get-event-dashboard', 'uses' => 'EventController@getDashboard']);
    Route::get('event/create', ['as' => 'get-create-event', 'uses' => 'EventController@createEvent']);
    
    Route::post('event/create', ['as' => 'post-create-event', 'uses' => 'EventController@postCreateEvent']);
    Route::post('event/attend', ['as' => 'post-attend-event', 'uses' => 'EventController@attendEvent']);
    Route::post('event/delete', ['as' => 'post-delete-event', 'uses' => 'EventController@deleteEvent']);
    Route::post('event/edit', ['as' => 'post-edit-event', 'uses' => 'EventController@editEvent']);
});

Route::get('event/{id?}', 'EventController@getIndex');
Route::get('category/{id?}', 'FilterController@getByCategory');
Route::get('event-type/{id?}', 'FilterController@getByType');
// Dummy routes

Route::get('generate', "HomeController@generateData");