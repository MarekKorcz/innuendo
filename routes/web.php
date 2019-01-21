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

Route::get('/employees', 'UserController@employeesList')->name('employees');
Route::get('/employee/{slack}', 'UserController@employee')->name('employee');
Route::get('/employee/calendar/{calendar_id}', 'UserController@calendar')->name('calendar');

Route::get('/property/index', 'PropertyController@index');
Route::get('/property/create', 'PropertyController@create');
Route::post('/property/store', 'PropertyController@store');
Route::get('/property/{id}', 'PropertyController@show');
Route::get('/property/{id}/edit', 'PropertyController@edit');
Route::put('/property/{id}', 'PropertyController@update');
Route::delete('/property/{id}', 'PropertyController@destroy');

Route::get('/calendar/{id}', 'CalendarController@create');

Route::get('/year/{id}', 'YearController@create');
Route::post('/year/store', 'YearController@store');
Route::get('/year/show/{id}', 'YearController@show');
Route::delete('/year/{id}', 'YearController@destroy');

Route::get('/month/{id}', 'MonthController@create');
Route::post('/month/store', 'MonthController@store');
Route::get('/month/show/{id}', 'MonthController@show');
Route::delete('/month/{id}', 'MonthController@destroy');

Route::get('/day/{id}', 'DayController@create');
Route::post('/day/store', 'DayController@store');
Route::get('/day/show/{id}', 'DayController@show');

Route::get('/graphic/{id}', 'GraphicController@create');
Route::post('/graphic/store', 'GraphicController@store');