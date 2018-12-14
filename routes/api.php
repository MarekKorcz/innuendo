<?php

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('register', 'Auth\RegisterController@register');
//    Route::post('reset-password-email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
//    Route::post('reset-password-store', 'Auth\ResetPasswordController@reset')->name('reset-password-store');
//    Route::post('set-password', 'AuthController@setPassword')->name('set-password');

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