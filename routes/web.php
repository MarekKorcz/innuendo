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

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BossController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\GraphicController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/subscriptions', [HomeController::class, 'subscriptions'])->name('subscriptions');
Route::get('/discounts', [HomeController::class, 'discounts'])->name('discounts');
Route::get('/private-policy', [HomeController::class, 'privatePolicy'])->name('private_policy');
Route::get('/regulations', [HomeController::class, 'regulations'])->name('regulations');
Route::get('/contact', [HomeController::class, 'contactPageShow'])->name('contact_page');
Route::post('/contact-update', [HomeController::class, 'contactPageUpdate']);
Route::post('/accept-terms', [HomeController::class, 'acceptTerms']);
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/promo/show', [HomeController::class, 'promoShow'])->name('promo');


// change language
Route::get('locale/{locale}', function ($locale) {
    Session::put('locale', $locale);

    return redirect()->back();
});

// register routings
Auth::routes();
Route::get('/temp-boss/register/{code}', [RegisterController::class, 'tempUserBossRegistrationCreate'])->name('tempBossRegisterAddress');
Route::post('/temp-boss/register/store', [RegisterController::class, 'tempUserBossRegistrationStore']);
Route::get('/temp-employee/register/{code}', [RegisterController::class, 'tempUserEmployeeRegistrationCreate'])->name('tempEmployeeRegisterAddress');
Route::post('/temp-employee/register/store', [RegisterController::class, 'tempUserEmployeeRegistrationStore']);
Route::post('/register/check-if-code-exists', [RegisterController::class, 'checkIfCodeExists']);
Route::post('/register/new-boss', [RegisterController::class, 'registerNewBoss']);

// >> admin routings
Route::get('/admin/user/list', [AdminController::class, 'userList']);
Route::get('/admin/user/show/{id}', [AdminController::class, 'userShow']);
Route::put('/admin/user/edit', [AdminController::class, 'userUpdate']);
Route::get('/admin/boss/list', [AdminController::class, 'bossList']);
Route::get('/admin/boss/show/{id}', [AdminController::class, 'bossShow']);
Route::post('/admin/get-potentially-new-bosses', [AdminController::class, 'getPotentiallyNewBosses']);
Route::put('/admin/boss/edit', [AdminController::class, 'bossUpdate']);
Route::get('/admin/temp-user/boss/show/{id}', [AdminController::class, 'tempUserBossShow']);
Route::get('/admin/employee/list', [AdminController::class, 'employeeList']);
Route::get('/admin/employee/create', [AdminController::class, 'employeeCreate']);
Route::post('/admin/employee/add', [AdminController::class, 'employeeAdd']);
Route::get('/admin/employee/show/{slug}', [AdminController::class, 'employeeShow']);
Route::post('/admin/employee/update', [AdminController::class, 'employeeUpdate']);
Route::get('/admin/temp-user/employee/send-activation-email/{id}', [AdminController::class, 'tempUserEmployeeSendActivationEmail']);

Route::get('/admin/boss/create', [AdminController::class, 'bossCreate']);
Route::post('/admin/boss/store', [AdminController::class, 'bossStore']);
Route::get('/admin/graphic-requests', [AdminController::class, 'graphicRequests']);
Route::get('/admin/graphic-request/{graphicRequestId}', [AdminController::class, 'graphicRequestShow']);
Route::post('/admin/make-a-message', [AdminController::class, 'makeAMessage']);
Route::get('/admin/promo/create', [AdminController::class, 'promoCreate']);
Route::post('/admin/promo/store', [AdminController::class, 'promoStore']);
Route::get('/admin/promo/show/{id}', [AdminController::class, 'promoShow']);
Route::get('/admin/promo/edit/{id}', [AdminController::class, 'promoEdit']);
Route::put('/admin/promo/update', [AdminController::class, 'promoUpdate']);
Route::get('/admin/promo/list', [AdminController::class, 'promoList']);
Route::get('/admin/promo-code/show/{id}', [AdminController::class, 'promoCodeShow']);
Route::post('/admin/make-a-promo-code-message', [AdminController::class, 'makeAPromoCodeMessage']);
Route::get('/admin/promo-code/message/change-status/{promoId}/{messageId}', [AdminController::class, 'promoCodeMessageChangeStatus']);
Route::get('/admin/promo/activation/toggle/{promoId}', [AdminController::class, 'promoActivationToggle']);
Route::get('/admin/approve/messages', [AdminController::class, 'approveMessages']);
Route::get('/admin/approve/messages/{bossId}/{promoId}', [AdminController::class, 'approveMessageShow']);
Route::get('/admin/contact/messages', [AdminController::class, 'contactMessages']);
Route::get('/admin/approve/message/status/change/{promoCodeId}', [AdminController::class, 'approveMessageStatusChange']);

// maybe all messages in one view?
// Route::get('/admin/user/messages', [AdminController::class, 'userMessages']);

Route::post('/admin/make-an-approve-message', [AdminController::class, 'makeAnApproveMessage']);
Route::get('/admin/discount/create', [DiscountController::class, 'create']);
Route::post('/admin/discount/store', [DiscountController::class, 'store']);
Route::get('/admin/discount/index', [DiscountController::class, 'index']);
Route::delete('/admin/discount/{id}', [DiscountController::class, 'destroy']);

// load image
Route::get('/userimage/{fileName}', [AdminController::class, 'getUserImage'])->name('account.image');

// >> employee routings
Route::get('/employee/backend-graphic', [WorkerController::class, 'graphicList'])->name('graphicList');
Route::get('/employee/backend-calendar/{property_id}/{year}/{month_number}/{day_number}', [WorkerController::class, 'backendCalendar'])->name('backendCalendar');
Route::post('/employee/get-graphic', [WorkerController::class, 'getEmployeeGraphic']);

Route::get('/employee/backend-appointment/show/{id}', [WorkerController::class, 'backendAppointmentShow'])->name('backendAppointmentShow');
Route::get('/employee/backend-appointment/index/{id}', [WorkerController::class, 'backendAppointmentIndex']);
Route::get('/employee/backend-users/index', [WorkerController::class, 'backendUsersIndex']);
Route::post('/employee/backend-appointment/set-appointment-status', [WorkerController::class, 'setAppointmentStatus']);
Route::post('/employee/backend-appointment/before-show-create-page', [WorkerController::class, 'beforeShowCreatePage']);
Route::post('/employee/backend-appointment/get-user-from-database', [WorkerController::class, 'getUserFromDatabase']);
Route::post('/employee/backend-appointment/get-items-from-database', [WorkerController::class, 'getItemsFromDatabase']);
Route::get('/employee/backend-appointment/create', [WorkerController::class, 'appointmentCreate']);
Route::post('/employee/backend-appointment/store', [WorkerController::class, 'appointmentStore']);
Route::get('/employee/backend-appointment/edit/{id}', [WorkerController::class, 'appointmentEdit']);
Route::put('/employee/backend-appointment', [WorkerController::class, 'appointmentUpdate']);

Route::get('/employees', [UserController::class, 'employeesList'])->name('employees');
Route::get('/employee/{slug}', [UserController::class, 'employee'])->name('employee');

Route::get('/property/index', [PropertyController::class, 'index'])->name('property_index');
Route::get('/property/create', [PropertyController::class, 'create']);
Route::post('/property/store', [PropertyController::class, 'store']);
Route::get('/property/{id}', [PropertyController::class, 'show']);
Route::get('/property/{id}/edit', [PropertyController::class, 'edit']);
Route::put('/property/{id}', [PropertyController::class, 'update']);
Route::get('/property/can-show/change/{id}', [PropertyController::class, 'canShowChange']);
Route::delete('/property/{id}', [PropertyController::class, 'destroy']);
Route::get('/property/invoice-data/create/{id}', [PropertyController::class, 'invoiceDataCreate']);
Route::post('/property/invoice-data/store', [PropertyController::class, 'invoiceDataStore']);
Route::get('/property/invoice-data/edit/{id}', [PropertyController::class, 'invoiceDataEdit']);
Route::post('/property/invoice-data/update', [PropertyController::class, 'invoiceDataUpdate']);
Route::get('/property/invoice/list/{id}', [PropertyController::class, 'invoiceList']);
Route::get('/property/invoice/{id}', [PropertyController::class, 'invoice']);
Route::get('/property/add-invoice/{id}', [PropertyController::class, 'addInvoice']);
Route::post('/property/add-invoice/update', [PropertyController::class, 'addInvoiceUpdate']);
Route::delete('/property/invoice/{id}', [PropertyController::class, 'invoiceDestroy']);
Route::get('/temp-property/{id}', [PropertyController::class, 'tempPropertyShow']);
Route::get('/temp-property/{id}/edit', [PropertyController::class, 'tempPropertyEdit']);
Route::put('/temp-property/{id}', [PropertyController::class, 'tempPropertyUpdate']);
Route::delete('/temp-property/{id}', [PropertyController::class, 'tempPropertyDestroy']);

Route::get('/year/{id}', [YearController::class, 'create']);
Route::post('/year/store', [YearController::class, 'store']);
Route::get('/year/show/{id}', [YearController::class, 'show']);
Route::delete('/year/{id}', [YearController::class, 'destroy']);

Route::get('/month/{id}', [MonthController::class, 'create']);
Route::post('/month/store', [MonthController::class, 'store']);
Route::get('/month/show/{id}', [MonthController::class, 'show']);
Route::delete('/month/{id}', [MonthController::class, 'destroy']);

Route::get('/day/{id}', [DayController::class, 'create']);
Route::post('/day/store', [DayController::class, 'store']);
Route::get('/day/show/{id}', [DayController::class, 'show']);

Route::get('/graphic/{id}', [GraphicController::class, 'create']);
Route::post('/graphic/store', [GraphicController::class, 'store']);
Route::get('/graphic/{id}/edit', [GraphicController::class, 'edit']);
Route::put('/graphic/update', [GraphicController::class, 'update']);

Route::post('/appointment/beforeShowCreatePage', [AppointmentController::class, 'beforeShowCreatePage']);
Route::get('/appointment/create', [AppointmentController::class, 'create']);
Route::post('/appointment/store', [AppointmentController::class, 'store']);

Route::get('/appointment/show/{id}', [UserController::class, 'appointmentShow'])->name('appointmentShow');
Route::get('/appointment/index', [UserController::class, 'appointmentIndex']);
Route::delete('/appointment/{id}', [UserController::class, 'appointmentDestroy']);

Route::get('/category/create', [CategoryController::class, 'create']);
Route::post('/category/store', [CategoryController::class, 'store']);
Route::get('/category/show/{id}', [CategoryController::class, 'show']);
Route::get('/category/{id}/edit', [CategoryController::class, 'edit']);
Route::put('/category/update', [CategoryController::class, 'update']);
Route::get('/category/index', [CategoryController::class, 'index']);

Route::get('/item/create/{id}', [ItemController::class, 'create']);
Route::post('/item/store', [ItemController::class, 'store']);
Route::get('/item/show/{id}', [ItemController::class, 'show']);
Route::get('/item/{id}/edit', [ItemController::class, 'edit']);
Route::put('/item/update', [ItemController::class, 'update']);

// user >> routings
//
// show properties list to user or boss
Route::get('/user/properties', [UserController::class, 'propertiesList'])->name('properties');

// user calendar
Route::get('/user/calendar/{property_id}/{year}/{month_number}/{day_number}', [UserController::class, 'calendar'])->name('calendar');
Route::post('/user/employee/get-graphic', [UserController::class, 'getEmployeeGraphic']);

// >> boss routings
// calendars routing
Route::get('/boss/calendar/{property_id}/{year}/{month_number}/{day_number}', [BossController::class, 'calendar']);
Route::post('/boss/employee/get-graphic', [BossController::class, 'getEmployeeGraphic']);

// code routings
Route::get('/boss/code', [BossController::class, 'code']);
Route::post('/boss/set-code', [BossController::class, 'setCode']);
Route::get('/code/add', [BossController::class, 'addCode']);
Route::delete('/code/{id}', [BossController::class, 'destroyCode']);

// property update
Route::get('/boss/property/{id}/edit', [BossController::class, 'propertyEdit']);
Route::put('/boss/property/update', [BossController::class, 'propertyUpdate']);

// boss and workers subscription appointment list
Route::get('/boss/property/appointments', [BossController::class, 'propertyAppointments']);
Route::get('/boss/worker/appointment/list/{propertyId}/{userId?}', [BossController::class, 'workerAppointmentList'])->name('workerAppointmentList');
Route::post('/boss/get-monthly-payments-for-done-appointments', [BossController::class, 'getMonthlyPaymentsForDoneAppointments']);
Route::post('/boss/get-property-users-from-database', [BossController::class, 'getPropertyUsersFromDatabase']);
Route::post('/boss/get-user-appointments-from-database', [BossController::class, 'getUserAppointmentsFromDatabase']);
Route::post('/boss/get-users-appointments-from-database', [BossController::class, 'getUsersAppointmentsFromDatabase']);

// worker show (temporarily off)
//Route::get('/boss/worker/show/{workerId}/{substartId?}/{intervalId?}', [BossController::class, 'workerShow']);

// subscription worker edit (you may need it later to turn on and off boss employees)
//Route::get('/boss/subscription/workers/edit/{substartId}/{intervalId}', [BossController::class, 'subscriptionWorkersEdit')->name('subscriptionWorkersEdit');
//Route::post('/boss/subscription/workers/update', [BossController::class, 'subscriptionWorkersUpdate']);

// invoices
Route::get('/boss/invoices', [BossController::class, 'invoices']);
Route::get('/boss/invoices/in/{propertyId}', [BossController::class, 'invoicesIn']);
Route::get('/boss/invoice/create/{propertyId}', [BossController::class, 'invoiceDataCreate']);
Route::post('/boss/subscription/invoice/store', [BossController::class, 'invoiceDataStore']);
Route::get('/boss/invoice/edit/{propertyId}', [BossController::class, 'invoiceDataEdit']);
Route::put('/boss/invoice/update', [BossController::class, 'invoiceDataUpdate']);

// graphic requests and approve messages
Route::post('/boss/make-a-graphic-request', [BossController::class, 'makeAGraphicRequest']);
Route::get('/boss/graphic-requests', [BossController::class, 'graphicRequests']);
Route::get('/boss/graphic-request/{graphicRequestId}', [BossController::class, 'graphicRequestShow']);
Route::get('/boss/graphic-request/edit/{graphicRequestId}', [BossController::class, 'graphicRequestEdit']);
Route::put('/boss/graphic-request/update', [BossController::class, 'graphicRequestUpdate']);
Route::post('/boss/make-a-message', [BossController::class, 'makeAMessage']);
Route::get('/boss/approve/messages', [BossController::class, 'approveMessages']);
Route::post('/boss/make-an-approve-message', [BossController::class, 'makeAnApproveMessage']);
Route::post('/boss/mark-message-as-displayed', [BossController::class, 'markMessageAsDisplayed']);

// Route::get('/test', [HomeController::class, 'test']);
