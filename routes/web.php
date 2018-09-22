<?php


//NOTE: SOME METHODS HERE RETURN A VIEW WHILE OTHERS RETURN AN API ENDPOINT

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads', 'ThreadsController@index')->name('threads');

Route::post('/threads', 'ThreadsController@store')->middleware('must-be-confirmed');

Route::get('/threads/create', 'ThreadsController@create');

Route::get("/threads/{channel}", 'ThreadsController@index');

Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');

Route::patch('/threads/{channel}/{thread}', 'ThreadsController@update')->name('thread.update');

Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');

Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');


Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');



Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');

Route::patch('/replies/{reply}', 'RepliesController@update');

Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');

Route::get('/profiles/{user}/', 'ProfilesController@show')->name('profile');

Route::get('/profiles/{user}/notifications/', 'UserNotificationsController@index');


Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy'); 

Route::get('/api/users', 'Api\UsersController@index');

Route::post('/api/users/{user}/avatar', 'Api\UsersAvatarController@store')->middleware('auth')->name('avatar');


Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');

Route::post('/locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');
Route::delete('/locked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');







