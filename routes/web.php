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
})->name('welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/employee/assign/{id}', 'EmployeeController@assign')->name('assign');
Route::post('/employee/assign/store', 'EmployeeController@store');

Route::get('/employee/backend-graphic', 'WorkerController@graphicList')->name('graphicList');
Route::get('/employee/backend-calendar/{calendar_id}/{year}/{month_number}/{day_number}', 'WorkerController@backendCalendar')->name('backendCalendar');
Route::get('/employee/backend-appointment/show/{id}', 'WorkerController@backendAppointmentShow');
Route::get('/employee/backend-appointment/index/{id}', 'WorkerController@backendAppointmentIndex');
Route::get('/employee/backend-appointment/index/temp-user/{id}', 'WorkerController@backendAppointmentIndexTempUser');
Route::post('/employee/backend-appointment/set-appointment-status', 'WorkerController@setAppointmentStatus');
Route::post('/employee/backend-appointment/before-show-create-page', 'WorkerController@beforeShowCreatePage');
Route::post('/employee/backend-appointment/get-user-from-database', 'WorkerController@getUserFromDatabase');
Route::get('/employee/backend-appointment/create', 'WorkerController@appointmentCreate');
Route::post('/employee/backend-appointment/store', 'WorkerController@appointmentStore');
Route::get('/employee/backend-appointment/edit/{id}', 'WorkerController@appointmentEdit');
Route::put('/employee/backend-appointment', 'WorkerController@appointmentUpdate');

Route::get('/employees', 'UserController@employeesList')->name('employees');
Route::get('/employee/{slug}', 'UserController@employee')->name('employee');
Route::get('/employee/calendar/{calendar_id}/{year}/{month_number}/{day_number}', 'UserController@calendar')->name('calendar');

Route::get('/user/properties', 'UserController@propertiesList')->name('properties');
Route::get('/user/property/{slug}', 'UserController@property')->name('property');

Route::get('/property/index', 'PropertyController@index');
Route::get('/property/create', 'PropertyController@create');
Route::post('/property/store', 'PropertyController@store');
Route::get('/property/{id}', 'PropertyController@show');
Route::get('/property/{id}/edit', 'PropertyController@edit');
Route::put('/property/{id}', 'PropertyController@update');
Route::delete('/property/{id}', 'PropertyController@destroy');

Route::get('/calendar/{id}', 'CalendarController@create');
Route::post('/calendar/activate/{id}', 'CalendarController@activate');
Route::post('/calendar/deactivate/{id}', 'CalendarController@deactivate');
Route::delete('/calendar/{id}', 'CalendarController@destroy');

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

Route::post('/appointment/beforeShowCreatePage', 'AppointmentController@beforeShowCreatePage');
Route::get('/appointment/create', 'AppointmentController@create');
Route::post('/appointment/store', 'AppointmentController@store');

Route::get('/appointment/show/{id}', 'UserController@appointmentShow');
Route::get('/appointment/index', 'UserController@appointmentIndex');
Route::delete('/appointment/{id}', 'UserController@appointmentDestroy');

Route::get('/subscription/create/{id}', 'SubscriptionController@create');
Route::post('/subscription/store', 'SubscriptionController@store');
Route::get('/subscription/show/{slug}', 'SubscriptionController@show');
Route::get('/subscription/index/property/{id}', 'SubscriptionController@propertySubscriptionIndex');
Route::get('/subscription/index', 'SubscriptionController@subscriptionIndex');
Route::delete('/subscription/{id}', 'SubscriptionController@destroy');
Route::post('/subscription/set-subscription-to-property', 'SubscriptionController@setSubscriptionToProperty');