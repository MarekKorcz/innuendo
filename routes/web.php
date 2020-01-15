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
Route::get('/admin/graphic-request/{graphicRequestId}/{chosenMessageId}', 'AdminController@graphicRequestShow');
Route::post('/admin/make-a-message', 'AdminController@makeAMessage');
Route::get('/admin/graphic-request/message/change-status/{graphicRequestId}/{messageId}', 'AdminController@graphicRequestMessageChangeStatus');
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
Route::get('/employee/assign/{id}', 'EmployeeController@assign')->name('assign');
Route::post('/employee/assign/store', 'EmployeeController@store');

// load image
Route::get('/userimage/{fileName}', [
   'uses' => 'AdminController@getUserImage',
    'as'  => 'account.image'
]);

// >> employee routings
Route::get('/employee/backend-graphic', 'WorkerController@graphicList')->name('graphicList');
Route::get('/employee/backend-calendar/{calendar_id}/{year}/{month_number}/{day_number}', 'WorkerController@backendCalendar')->name('backendCalendar');
Route::get('/employee/backend-appointment/show/{id}', 'WorkerController@backendAppointmentShow');
Route::get('/employee/backend-appointment/index/{id}', 'WorkerController@backendAppointmentIndex');
Route::get('/employee/backend-appointment/index/temp-user/{id}', 'WorkerController@backendAppointmentIndexTempUser');
Route::get('/employee/backend-users/index', 'WorkerController@backendUsersIndex');
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
Route::get('/property/can-show/change/{id}', 'PropertyController@canShowChange');
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
Route::get('/graphic/{id}/edit', 'GraphicController@edit');
Route::put('/graphic/update', 'GraphicController@update');

Route::post('/appointment/beforeShowCreatePage', 'AppointmentController@beforeShowCreatePage');
Route::get('/appointment/create', 'AppointmentController@create');
Route::post('/appointment/store', 'AppointmentController@store');

Route::get('/appointment/show/{id}', 'UserController@appointmentShow');
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
// user calendar
Route::get('/user/calendar/{calendar_id}/{year}/{month_number}/{day_number}', 'UserController@calendar')->name('calendar');

// show employees to user
Route::get('/user/properties', 'UserController@propertiesList')->name('properties');
Route::get('/user/property/{id}', 'UserController@property')->name('property');

// user subscription dashboard
Route::get('/user/subscription/list/{substartId}', 'UserController@subscriptionList');
Route::post('/user/get/property/subscription', 'UserController@getPropertySubscriptions');
Route::post('/user/get/subscription/substarts', 'UserController@getSubscriptionSubstarts');

// user show purchased subscription and its appointment
Route::get('/user/subscription/purchased/show/{id}', 'UserController@subscriptionPurchasedShow')->name('subscriptionPurchasedShow');
Route::post('/user/get-user-appointments-from-database', 'UserController@getUserAppointmentsFromDatabase');

// >> boss routings
// calendars routing
Route::get('/boss/calendar/{property_id}/{year}/{month_number}/{day_number}', 'BossController@calendar');

// code routings
Route::get('/boss/codes', 'BossController@codes');
Route::post('/boss/set-code', 'BossController@setCode');
Route::get('/code/add', 'BossController@addCode');
Route::delete('/code/{id}', 'BossController@destroyCode');
Route::post('/subscription/set-subscription-to-chosen-property-subscription', 'BossController@setSubscriptionToChosenPropertySubscription');
Route::post('/subscription/set-chosen-property', 'BossController@setChosenProperty');
Route::post('/subscription/delete-chosen-property', 'BossController@deleteChosenProperty');

// property update
Route::get('/boss/property/{id}/edit', 'BossController@propertyEdit');
Route::put('/boss/property/update', 'BossController@propertyUpdate');

// subscription purchase
Route::get('/boss/subscription/purchase/{propertyId}/{subscriptionId}', 'BossController@subscriptionPurchase')->name('subscriptionPurchaseView');
Route::post('/boss/subscription/purchased', 'BossController@subscriptionPurchased');
Route::post('/boss/subscription/purchased/refresh', 'BossController@subscriptionPurchasedRefresh');

// boss and workers subscription appointment list
Route::get('/boss/worker/appointment/list/{substartId}/{userId}', 'BossController@workerAppointmentList')->name('workerAppointmentList');
Route::post('/boss/get-subscription-users-from-database', 'BossController@getSubscriptionUsersFromDatabase');
Route::post('/boss/get-user-appointments-from-database', 'BossController@getUserAppointmentsFromDatabase');
Route::post('/boss/get-users-appointments-from-database', 'BossController@getUsersAppointmentsFromDatabase');

// worker show (temporarily off)
//Route::get('/boss/worker/show/{workerId}/{substartId?}/{intervalId?}', 'BossController@workerShow');

// subscription worker edit
Route::get('/boss/subscription/workers/edit/{substartId}/{intervalId}', 'BossController@subscriptionWorkersEdit')->name('subscriptionWorkersEdit');
Route::post('/boss/subscription/workers/update', 'BossController@subscriptionWorkersUpdate');

// invoices
Route::get('/boss/subscription/invoice/create/{substartId}', 'BossController@invoiceDataCreate');
Route::post('/boss/subscription/invoice/store', 'BossController@invoiceDataStore');
Route::get('/boss/subscription/invoice/{intervalId}', 'BossController@subscriptionInvoice');
Route::get('/boss/subscription/invoices/{substartId}', 'BossController@subscriptionInvoices')->name('subscriptionInvoices');
Route::get('/boss/subscription/invoice/edit/{invoiceDataId}/{substartId}', 'BossController@invoiceDataEdit');
Route::put('/boss/subscription/invoice/update', 'BossController@invoiceDataUpdate');

// graphic requests and approve messages
Route::post('/boss/make-a-graphic-request', 'BossController@makeAGraphicRequest');
Route::get('/boss/graphic-requests', 'BossController@graphicRequests');
Route::get('/boss/graphic-request/{graphicRequestId}/{chosenMessageId}', 'BossController@graphicRequestShow');
Route::get('/boss/graphic-request/edit/{graphicRequestId}', 'BossController@graphicRequestEdit');
Route::put('/boss/graphic-request/update', 'BossController@graphicRequestUpdate');
Route::post('/boss/make-a-message', 'BossController@makeAMessage');
Route::get('/boss/approve/messages', 'BossController@approveMessages');
Route::post('/boss/make-an-approve-message', 'BossController@makeAnApproveMessage');
Route::post('/boss/mark-message-as-displayed', 'BossController@markMessageAsDisplayed');

//Route::get('/test', 'HomeController@test');