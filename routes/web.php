<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Home
Route::get('/', 'Auth\LoginController@home');

// Cards
//Route::get('cards', 'CardController@list');
//Route::get('cards/{id}', 'CardController@show');

// API
//Route::put('api/cards', 'CardController@create');
//Route::delete('api/cards/{card_id}', 'CardController@delete');
//Route::put('api/cards/{card_id}/', 'ItemController@create');
//Route::post('api/item/{id}', 'ItemController@update');
//Route::delete('api/item/{id}', 'ItemController@delete');

Route::post('api/post', 'PostController@create');
Route::post('api/post/{id}', 'PostController@delete');


// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');



Route::get('about', 'AboutController@show')->name('about');
Route::get('contacts', 'ContactsController@show')->name('contacts');
Route::get('admin', 'AdminController@show')->name('admin');
Route::get('home', 'HomeController@show')->name('home');

// TODO: Nestes é preciso passar dps os argumentos corretos 
// para construir as paginas sem ser apenas valores placeholders
Route::get('post/{id}', 'PostController@show')->name('post');
Route::get('profile/{username}', 'ProfileController@show')->name('profile');
Route::get('group/{username}', 'GroupController@show')->name('group');
Route::get('search/{query}', 'SearchController@show')->name('search');
Route::get('messages/{sender_username}', 'MessagesController@show')->name('messages');
