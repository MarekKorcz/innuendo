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

Route::get('/', function () 
{
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin', 'HomeController@admin')->middleware('admin');

Route::get('/employee', 'HomeController@employee')->middleware('employee');

Route::get('/property/index', 'PropertyController@index');
Route::get('/property/create', 'PropertyController@create');
Route::post('/property/store', 'PropertyController@store');
Route::get('/property/{id}', 'PropertyController@show');
Route::get('/property/{id}/edit', 'PropertyController@edit');
Route::put('/property/{id}', 'PropertyController@update');
Route::delete('/property/{id}', 'PropertyController@destroy');

Route::get('/calendar/{id}', 'CalendarController@create');