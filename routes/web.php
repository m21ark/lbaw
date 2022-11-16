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

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');


// Main PAGES
Route::get('about', 'AboutController@show')->name('about');
Route::get('contacts', 'ContactsController@show')->name('contacts');
Route::get('admin', 'AdminController@show')->name('admin');
Route::get('home', 'HomeController@show')->name('home');

// TODO: Nestes é preciso passar dps os argumentos corretos
// para construir as paginas sem ser apenas valores placeholders
Route::get('post/{id}', 'PostController@show')->name('post');
Route::get('profile/{username}', 'ProfileController@show')->name('profile');
Route::get('group/{name}', 'GroupController@show')->name('group');
Route::get('search/{query}', 'SearchController@show')->name('search');
Route::get('messages/{sender_username}', 'MessagesController@show')->name('messages');


Route::get('api/post/feed/{type_feed}', 'PostController@feed');
Route::post('api/post', 'PostController@create');
Route::post('api/post/{id}', 'PostController@delete');

// ======================================= APIS ========================================

// Create group
Route::post('api/group', 'GroupController@create');
Route::delete('api/group/{id}', 'GroupController@delete');

// Group Add/Remove Owner
Route::post('api/group_owner/{id}', 'GroupController@addGroupOwner'); // talvez n seja post
Route::delete('api/group_owner/{id}', 'GroupController@removeGroupOwner');

// VER AS ROUTES USADAS
// addGroupMember($idUser, $idGroup)
// removeGroupMember($idMember)

// Group Add/Remove Member
Route::post('api/group_member/', 'GroupController@addGroupMember');
Route::delete('api/group_member/{id}', 'GroupController@removeGroupMember');

Route::get('api/group_members/{id}', 'GroupController@getGroupMembers');

// User Make/Delete Post
Route::post('api/post', 'PostController@create');
Route::delete('api/post/{id}', 'PostController@delete');
