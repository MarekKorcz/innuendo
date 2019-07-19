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

Route::get('/temp-boss/register/{code}', 'Auth\RegisterController@tempUserBossRegistrationCreate')->name('tempBossRegisterAddress');
Route::post('/temp-boss/register/store', 'Auth\RegisterController@tempUserBossRegistrationStore');
Route::post('/register/check-if-code-exists', 'Auth\RegisterController@checkIfCodeExists');
Route::post('/register/new-boss', 'Auth\RegisterController@registerNewBoss');

Route::get('/admin/user/list', 'AdminController@userLisAuth\t');
Route::get('/admin/boss/list', 'AdminController@bossList');
Route::get('/admin/employee/list', 'AdminController@employeeList');
Route::get('/admin/user/show/{id}', 'AdminController@userShow');
Route::get('/admin/boss/show/{id}', 'AdminController@bossShow');
Route::get('/admin/temp-user/boss/show/{id}', 'AdminController@tempUserBossShow');
Route::get('/admin/temp-user/user/show/{id}', 'AdminController@tempUserUserShow');
Route::get('/admin/employee/show/{id}', 'AdminController@employeeShow');
Route::put('/admin/user/edit', 'AdminController@userEdit');
Route::get('/admin/boss/create', 'AdminController@bossCreate');
Route::post('/admin/boss/store', 'AdminController@bossStore');
Route::get('/admin/graphic-requests', 'AdminController@graphicRequests');
Route::get('/admin/graphic-request/{graphicRequestId}/{chosenMessageId}', 'AdminController@graphicRequestShow');
Route::post('/admin/make-a-message', 'AdminController@makeAMessage');
Route::get('/admin/graphic-request/message/change-status/{graphicRequestId}/{messageId}', 'AdminController@graphicRequestMessageChangeStatus');
Route::get('/admin/approve/messages', 'AdminController@approveMessages');
Route::get('/admin/approve/messages/{bossId}', 'AdminController@approveMessageShow');
Route::get('/admin/approve/message/status/change/{promoCodeId}', 'AdminController@approveMessageStatusChange');
Route::post('/admin/make-an-approve-message', 'AdminController@makeAnApproveMessage');

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
Route::post('/employee/backend-appointment/get-user-items-from-database', 'WorkerController@getUserItemsFromDatabase');
Route::get('/employee/backend-appointment/create', 'WorkerController@appointmentCreate');
Route::post('/employee/backend-appointment/store', 'WorkerController@appointmentStore');
Route::get('/employee/backend-appointment/edit/{id}', 'WorkerController@appointmentEdit');
Route::put('/employee/backend-appointment', 'WorkerController@appointmentUpdate');
Route::get('/employee/activate-subscription/{purchase_id}/{appointment_id}', 'WorkerController@activateSubscription');

Route::get('/employees', 'UserController@employeesList')->name('employees');
Route::get('/employee/{slug}', 'UserController@employee')->name('employee');
Route::get('/employee/calendar/{calendar_id}/{year}/{month_number}/{day_number}', 'UserController@calendar')->name('calendar');

Route::get('/property/index', 'PropertyController@index')->name('property_index');
Route::get('/property/create', 'PropertyController@create');
Route::post('/property/store', 'PropertyController@store');
Route::get('/property/{id}', 'PropertyController@show');
Route::get('/temp-property/{id}', 'PropertyController@tempPropertyShow');
Route::get('/property/{id}/edit', 'PropertyController@edit');
Route::get('/temp-property/{id}/edit', 'PropertyController@tempPropertyEdit');
Route::put('/property/{id}', 'PropertyController@update');
Route::put('/temp-property/{id}', 'PropertyController@tempPropertyUpdate');
Route::delete('/property/{id}', 'PropertyController@destroy');
Route::delete('/temp-property/{id}', 'PropertyController@tempPropertyDestroy');

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
Route::get('/subscription/show/{id}', 'SubscriptionController@show');
Route::get('/subscription/index', 'SubscriptionController@subscriptionIndex');
Route::delete('/subscription/{id}', 'SubscriptionController@destroy');
Route::post('/subscription/set-subscription-to-property', 'SubscriptionController@setSubscriptionToProperty');
Route::post('/subscription/set-subscription-to-temporary-property', 'SubscriptionController@setSubscriptionToTemporaryProperty');
Route::post('/subscription/set-item-to-subscription', 'SubscriptionController@setItemToSubscription');
Route::get('/subscription/{id}/edit', 'SubscriptionController@edit');
Route::put('/subscription/update', 'SubscriptionController@update');

Route::get('/user/properties', 'UserController@propertiesList')->name('properties');
Route::get('/user/property/{id}', 'UserController@property')->name('property');
Route::get('/user/properties/subscription', 'UserController@propertiesSubscription');
Route::get('/user/property/subscription/list/{id}', 'UserController@propertySubscriptionList');
Route::get('/user/subscription/purchased/property/list', 'UserController@purchasedSubscriptionPropertyList');
Route::get('/user/subscription/list/purchased/{propertyId}', 'UserController@purchasedSubscriptionList');
// po co ten routing??
Route::get('/user/subscription/show/{propertyId}/{subscriptionId}', 'UserController@subscriptionShow');
Route::get('/user/subscription/purchase/{propertyId}/{subscriptionId}', 'UserController@subscriptionPurchase');
Route::post('/user/subscription/purchased', 'UserController@subscriptionPurchased');
Route::get('/user/subscription/purchased/show/{id}', 'UserController@subscriptionPurchasedShow');

Route::get('/boss/codes', 'BossController@codes');
Route::post('/boss/set-code', 'BossController@setCode');
Route::get('/code/add', 'BossController@addCode');
Route::delete('/code/{id}', 'BossController@destroyCode');
//Route::get('/boss/property/list', 'BossController@propertyList');
//Route::get('/boss/property/{id}', 'BossController@property');
Route::get('/boss/property/{id}/edit', 'BossController@propertyEdit');
Route::put('/boss/property/update', 'BossController@propertyUpdate');
Route::get('/boss/subscription/list/{propertyId}/{subscriptionId}', 'BossController@subscriptionList');
Route::get('/boss/properties/subscription/purchase', 'BossController@propertiesSubscriptionPurchase');
//Route::get('/boss/property/subscriptions/purchase/{id}', 'BossController@propertySubscriptionsPurchase');
Route::get('/boss/subscription/purchase/{propertyId}/{subscriptionId}', 'BossController@subscriptionPurchase');
Route::post('/boss/subscription/purchased', 'BossController@subscriptionPurchased');
Route::get('/boss/worker/appointment/list/{substartId}/{userId}', 'BossController@workerAppointmentList');
Route::get('/boss/subscription/workers/edit/{substartId}/{intervalId}', 'BossController@subscriptionWorkersEdit');
Route::post('/boss/subscription/workers/update', 'BossController@subscriptionWorkersUpdate');
Route::get('/boss/subscription/invoices/{substartId}', 'BossController@subscriptionInvoices');
Route::get('/boss/subscription/invoice/create/{substartId}', 'BossController@invoiceDataCreate');
Route::post('/boss/subscription/invoice/store', 'BossController@invoiceDataStore');
Route::get('/boss/subscription/invoice/edit/{invoiceDataId}/{substartId}', 'BossController@invoiceDataEdit');
Route::put('/boss/subscription/invoice/update', 'BossController@invoiceDataUpdate');
Route::get('/boss/subscription/invoice/{intervalId}', 'BossController@subscriptionInvoice');
Route::post('/boss/make-a-graphic-request', 'BossController@makeAGraphicRequest');
Route::get('/boss/graphic-requests', 'BossController@graphicRequests');
Route::get('/boss/graphic-request/{graphicRequestId}/{chosenMessageId}', 'BossController@graphicRequestShow');
Route::get('/boss/graphic-request/edit/{graphicRequestId}', 'BossController@graphicRequestEdit');
Route::put('/boss/graphic-request/update', 'BossController@graphicRequestUpdate');
Route::post('/boss/make-a-message', 'BossController@makeAMessage');
Route::get('/boss/approve/messages', 'BossController@approveMessages');
Route::post('/boss/make-an-approve-message', 'BossController@makeAnApproveMessage');
Route::post('/subscription/set-subscription-to-chosen-property-subscription', 'BossController@setSubscriptionToChosenPropertySubscription');
Route::post('/subscription/set-chosen-property', 'BossController@setChosenProperty');
Route::post('/boss/get/property/subscription', 'BossController@getPropertySubscriptions');
Route::post('/boss/get/subscription/substarts', 'BossController@getSubscriptionSubstarts');
Route::post('/boss/get/subscription/workers', 'BossController@getSubscriptionWorkers');
Route::post('/subscription/delete-chosen-property', 'BossController@deleteChosenProperty');
Route::post('/boss/get-subscription-users-from-database', 'BossController@getSubscriptionUsersFromDatabase');
Route::post('/boss/get-user-appointments-from-database', 'BossController@getUserAppointmentsFromDatabase');
Route::post('/boss/get-users-appointments-from-database', 'BossController@getUsersAppointmentsFromDatabase');
Route::post('/boss/mark-message-as-displayed', 'BossController@markMessageAsDisplayed');

//Route::get('/test', 'HomeController@test');