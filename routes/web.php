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

// public routings
Route::get('/', 'HomeController@welcome')->name('welcome');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/subscriptions', 'HomeController@subscriptions')->name('subscriptions');
Route::get('/discounts', 'HomeController@discounts')->name('discounts');
Route::get('/private-policy', 'HomeController@privatePolicy')->name('private_policy');
Route::get('/regulations', 'HomeController@regulations')->name('regulations');
Route::get('/contact', 'HomeController@contactPageShow')->name('contact_page');
Route::post('/contact-update', 'HomeController@contactPageUpdate');
Route::post('/accept-terms', 'HomeController@acceptTerms');
Route::get('/about', 'HomeController@about')->name('about');
Route::get('/promo/show', 'HomeController@promoShow')->name('promo');

// change language
Route::get('locale/{locale}', function($locale) {
    
    Session::put('locale', $locale);
    
    return redirect()->back();
});

// register routings
Auth::routes();
Route::get('/temp-boss/register/{code}', 'Auth\RegisterController@tempUserBossRegistrationCreate')->name('tempBossRegisterAddress');
Route::post('/temp-boss/register/store', 'Auth\RegisterController@tempUserBossRegistrationStore');
Route::get('/temp-employee/register/{code}', 'Auth\RegisterController@tempUserEmployeeRegistrationCreate')->name('tempEmployeeRegisterAddress');
Route::post('/temp-employee/register/store', 'Auth\RegisterController@tempUserEmployeeRegistrationStore');
Route::post('/register/check-if-code-exists', 'Auth\RegisterController@checkIfCodeExists');
Route::post('/register/new-boss', 'Auth\RegisterController@registerNewBoss');

// >> admin routings
Route::get('/admin/user/list', 'AdminController@userList');
Route::get('/admin/boss/list', 'AdminController@bossList');
Route::get('/admin/employee/list', 'AdminController@employeeList');
Route::get('/admin/user/show/{id}', 'AdminController@userShow');
Route::get('/admin/boss/show/{id}', 'AdminController@bossShow');
Route::get('/admin/temp-user/boss/show/{id}', 'AdminController@tempUserBossShow');
Route::get('/admin/temp-user/user/show/{id}', 'AdminController@tempUserUserShow');
Route::get('/admin/employee/create', 'AdminController@employeeCreate');
Route::post('/admin/employee/add', 'AdminController@employeeAdd');
Route::get('/admin/temp-user/employee/send-activation-email/{id}', 'AdminController@tempUserEmployeeSendActivationEmail');
Route::get('/admin/employee/show/{id}', 'AdminController@employeeShow');
Route::post('/admin/employee/update', 'AdminController@employeeUpdate');
Route::put('/admin/user/edit', 'AdminController@userEdit');
Route::get('/admin/boss/create', 'AdminController@bossCreate');
Route::post('/admin/boss/store', 'AdminController@bossStore');
Route::get('/admin/graphic-requests', 'AdminController@graphicRequests');
Route::get('/admin/graphic-request/{graphicRequestId}', 'AdminController@graphicRequestShow');
Route::post('/admin/make-a-message', 'AdminController@makeAMessage');
Route::get('/admin/promo/create', 'AdminController@promoCreate');
Route::post('/admin/promo/store', 'AdminController@promoStore');
Route::get('/admin/promo/show/{id}', 'AdminController@promoShow');
Route::get('/admin/promo/edit/{id}', 'AdminController@promoEdit');
Route::put('/admin/promo/update', 'AdminController@promoUpdate');
Route::get('/admin/promo/list', 'AdminController@promoList');
Route::get('/admin/promo-code/show/{id}', 'AdminController@promoCodeShow');
Route::get('/admin/invoice-data/create', 'AdminController@invoiceDataCreate');
Route::post('/admin/invoice-data/store', 'AdminController@invoiceDataStore');
Route::get('/admin/invoice-data/list', 'AdminController@invoiceDataList');
Route::delete('/admin/invoice-data/{id}', 'AdminController@invoiceDataSoftDelete');
Route::get('/admin/invoice-data/undelete/{id}', 'AdminController@invoiceDataUndelete');
Route::delete('/admin/invoice-data/hard-delete/{id}', 'AdminController@invoiceDataHardDelete');
Route::post('/admin/make-a-promo-code-message', 'AdminController@makeAPromoCodeMessage');
Route::get('/admin/promo-code/message/change-status/{promoId}/{messageId}', 'AdminController@promoCodeMessageChangeStatus');
Route::get('/admin/promo/activation/toggle/{promoId}', 'AdminController@promoActivationToggle');
Route::get('/admin/approve/messages', 'AdminController@approveMessages');
Route::get('/admin/approve/messages/{bossId}/{promoId}', 'AdminController@approveMessageShow');
Route::get('/admin/contact/messages', 'AdminController@contactMessages');
Route::get('/admin/approve/message/status/change/{promoCodeId}', 'AdminController@approveMessageStatusChange');
Route::post('/admin/make-an-approve-message', 'AdminController@makeAnApproveMessage');
Route::get('/admin/discount/create', 'DiscountController@create');
Route::post('/admin/discount/store', 'DiscountController@store');
Route::get('/admin/discount/index', 'DiscountController@index');
Route::delete('/admin/discount/{id}', 'DiscountController@destroy');

// load image
Route::get('/userimage/{fileName}', [
   'uses' => 'AdminController@getUserImage',
    'as'  => 'account.image'
]);

// >> employee routings
Route::get('/employee/backend-graphic', 'WorkerController@graphicList')->name('graphicList');
Route::get('/employee/backend-calendar/{property_id}/{year}/{month_number}/{day_number}', 'WorkerController@backendCalendar')->name('backendCalendar');
Route::post('/employee/get-graphic', 'WorkerController@getEmployeeGraphic');

Route::get('/employee/backend-appointment/show/{id}', 'WorkerController@backendAppointmentShow')->name('backendAppointmentShow');
Route::get('/employee/backend-appointment/index/{id}', 'WorkerController@backendAppointmentIndex');
Route::get('/employee/backend-users/index', 'WorkerController@backendUsersIndex');
Route::post('/employee/backend-appointment/set-appointment-status', 'WorkerController@setAppointmentStatus');
Route::post('/employee/backend-appointment/before-show-create-page', 'WorkerController@beforeShowCreatePage');
Route::post('/employee/backend-appointment/get-user-from-database', 'WorkerController@getUserFromDatabase');
Route::post('/employee/backend-appointment/get-items-from-database', 'WorkerController@getItemsFromDatabase');
Route::get('/employee/backend-appointment/create', 'WorkerController@appointmentCreate');
Route::post('/employee/backend-appointment/store', 'WorkerController@appointmentStore');
Route::get('/employee/backend-appointment/edit/{id}', 'WorkerController@appointmentEdit');
Route::put('/employee/backend-appointment', 'WorkerController@appointmentUpdate');

Route::get('/employees', 'UserController@employeesList')->name('employees');
Route::get('/employee/{slug}', 'UserController@employee')->name('employee');

Route::get('/property/index', 'PropertyController@index')->name('property_index');
Route::get('/property/create', 'PropertyController@create');
Route::post('/property/store', 'PropertyController@store');
Route::get('/property/{id}', 'PropertyController@show');
Route::get('/property/{id}/edit', 'PropertyController@edit');
Route::put('/property/{id}', 'PropertyController@update');
Route::get('/property/can-show/change/{id}', 'PropertyController@canShowChange');
Route::delete('/property/{id}', 'PropertyController@destroy');
Route::get('/temp-property/{id}', 'PropertyController@tempPropertyShow');
Route::get('/temp-property/{id}/edit', 'PropertyController@tempPropertyEdit');
Route::put('/temp-property/{id}', 'PropertyController@tempPropertyUpdate');
Route::delete('/temp-property/{id}', 'PropertyController@tempPropertyDestroy');

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
Route::get('/graphic/{id}/edit', 'GraphicController@edit');
Route::put('/graphic/update', 'GraphicController@update');

Route::post('/appointment/beforeShowCreatePage', 'AppointmentController@beforeShowCreatePage');
Route::get('/appointment/create', 'AppointmentController@create');
Route::post('/appointment/store', 'AppointmentController@store');

Route::get('/appointment/show/{id}', 'UserController@appointmentShow')->name('appointmentShow');
Route::get('/appointment/index', 'UserController@appointmentIndex');
Route::delete('/appointment/{id}', 'UserController@appointmentDestroy');

Route::get('/category/create', 'CategoryController@create');
Route::post('/category/store', 'CategoryController@store');
Route::get('/category/show/{id}', 'CategoryController@show');
Route::get('/category/{id}/edit', 'CategoryController@edit');
Route::put('/category/update', 'CategoryController@update');
Route::get('/category/index', 'CategoryController@index');

Route::get('/item/create/{id}', 'ItemController@create');
Route::post('/item/store', 'ItemController@store');
Route::get('/item/show/{id}', 'ItemController@show');
Route::get('/item/{id}/edit', 'ItemController@edit');
Route::put('/item/update', 'ItemController@update');

// user >> routings
// 
// show properties list to user or boss
Route::get('/user/properties', 'UserController@propertiesList')->name('properties');

// user calendar
Route::get('/user/calendar/{property_id}/{year}/{month_number}/{day_number}', 'UserController@calendar')->name('calendar');
Route::post('/user/employee/get-graphic', 'UserController@getEmployeeGraphic');

// >> boss routings
// calendars routing
Route::get('/boss/calendar/{property_id}/{year}/{month_number}/{day_number}', 'BossController@calendar');
Route::post('/boss/employee/get-graphic', 'BossController@getEmployeeGraphic');

// code routings
Route::get('/boss/code', 'BossController@code');
Route::post('/boss/set-code', 'BossController@setCode');
Route::get('/code/add', 'BossController@addCode');
Route::delete('/code/{id}', 'BossController@destroyCode');

// property update
Route::get('/boss/property/{id}/edit', 'BossController@propertyEdit');
Route::put('/boss/property/update', 'BossController@propertyUpdate');

// boss and workers subscription appointment list
Route::get('/boss/property/appointments', 'BossController@propertyAppointments');
Route::get('/boss/worker/appointment/list/{propertyId}/{userId?}', 'BossController@workerAppointmentList')->name('workerAppointmentList');
Route::post('/boss/get-monthly-payments-for-done-appointments', 'BossController@getMonthlyPaymentsForDoneAppointments');
Route::post('/boss/get-property-users-from-database', 'BossController@getPropertyUsersFromDatabase');
Route::post('/boss/get-user-appointments-from-database', 'BossController@getUserAppointmentsFromDatabase');
Route::post('/boss/get-users-appointments-from-database', 'BossController@getUsersAppointmentsFromDatabase');

// worker show (temporarily off)
//Route::get('/boss/worker/show/{workerId}/{substartId?}/{intervalId?}', 'BossController@workerShow');

// subscription worker edit (you may need it later to turn on and off boss employees)
//Route::get('/boss/subscription/workers/edit/{substartId}/{intervalId}', 'BossController@subscriptionWorkersEdit')->name('subscriptionWorkersEdit');
//Route::post('/boss/subscription/workers/update', 'BossController@subscriptionWorkersUpdate');

// invoices (later when, invoice views will be done)
//Route::get('/boss/subscription/invoice/create/{substartId}', 'BossController@invoiceDataCreate');
//Route::post('/boss/subscription/invoice/store', 'BossController@invoiceDataStore');
//Route::get('/boss/subscription/invoice/{intervalId}', 'BossController@subscriptionInvoice');
//Route::get('/boss/subscription/invoices/{substartId}', 'BossController@subscriptionInvoices')->name('subscriptionInvoices');
//Route::get('/boss/subscription/invoice/edit/{invoiceDataId}/{substartId}', 'BossController@invoiceDataEdit');
//Route::put('/boss/subscription/invoice/update', 'BossController@invoiceDataUpdate');

// graphic requests and approve messages
Route::post('/boss/make-a-graphic-request', 'BossController@makeAGraphicRequest');
Route::get('/boss/graphic-requests', 'BossController@graphicRequests');
Route::get('/boss/graphic-request/{graphicRequestId}', 'BossController@graphicRequestShow');
Route::get('/boss/graphic-request/edit/{graphicRequestId}', 'BossController@graphicRequestEdit');
Route::put('/boss/graphic-request/update', 'BossController@graphicRequestUpdate');
Route::post('/boss/make-a-message', 'BossController@makeAMessage');
Route::get('/boss/approve/messages', 'BossController@approveMessages');
Route::post('/boss/make-an-approve-message', 'BossController@makeAnApproveMessage');
Route::post('/boss/mark-message-as-displayed', 'BossController@markMessageAsDisplayed');

//Route::get('/test', 'HomeController@test');