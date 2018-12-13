<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::group([
//    'prefix' => 'admin'
//], function () {
//    Route::group([
//        'prefix' => 'vendor'
//    ], function () {
//        Route::get('/all', 'AdminController@index');
//        Route::delete('{shop}', 'AdminController@delete');
//    });
//});

Route::group([
    'prefix' => 'vendor'
], function () {
    Route::post('/store', 'VendorController@store');
    Route::put('/update', 'VendorController@update');
    Route::get('/show', 'VendorController@show');
    Route::get('/orders', 'VendorController@orders');
    Route::get('{order}', 'VendorController@showOrder');
    Route::put('{order}', 'VendorController@updateOrder');
    Route::delete('{order}', 'VendorController@deleteOrder');
});

Route::group([
    'prefix' => 'backend'
], function () {
    Route::group([
        'prefix' => 'item'
    ], function () {
        Route::get('/index', 'BackendItemController@index');
        Route::get('{item}', 'BackendItemController@show');
        Route::post('/store', 'BackendItemController@store');
        Route::put('{item}', 'BackendItemController@update');
        Route::delete('{item}', 'BackendItemController@delete');
    });
    Route::group([
        'prefix' => 'category'
    ], function () {        
        Route::get('/index', 'BackendCategoryController@index');
        Route::get('{category}', 'BackendCategoryController@show');
        Route::post('/store', 'BackendCategoryController@store');
        Route::put('{category}', 'BackendCategoryController@update');
        Route::delete('{category}', 'BackendCategoryController@delete');
    });
});

Route::group([
    'prefix' => 'frontend'
], function () {
    Route::group([
        'prefix' => 'item'
    ], function () {
        Route::get('{vendorName}', 'FrontendItemController@index');
        Route::get('{item}', 'FrontendItemController@show');
    });
    Route::group([
        'prefix' => 'category'
    ], function () {
        Route::get('{categoryName}', 'FrontendCategoryController@index');
        Route::get('{category}', 'FrontendCategoryController@show');
    });
});

Route::group([
    'prefix' => 'order'
], function () {
    Route::get('/index', 'OrderController@index');
    Route::get('/show', 'OrderController@show');
    Route::post('/store', 'OrderController@store');
});