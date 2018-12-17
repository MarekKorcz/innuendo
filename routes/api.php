<?php



/**
 * 
 * 
 * 
 *          Polish (and not only polish) character validation
 * 
 * 
 * 
 */







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
    'prefix' => 'backend'
], function () {
    Route::group([
        'prefix' => 'vendor'
    ], function () {
        Route::post('/store', 'BackendVendorController@store');
        Route::put('/update', 'BackendVendorController@update');
        Route::get('/show', 'BackendVendorController@show');
        Route::get('/orders', 'BackendVendorController@orders');
        Route::get('{order}', 'BackendVendorController@showOrder');
        Route::put('{order}', 'BackendVendorController@updateOrder');
        Route::delete('{order}', 'BackendVendorController@deleteOrder');
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
    Route::group([
        'prefix' => 'item'
    ], function () {
        Route::get('/index', 'BackendItemController@index');
        Route::get('{categorySlug}/{item}', 'BackendItemController@show');
        Route::post('{categorySlug}', 'BackendItemController@store');
        Route::put('{item}', 'BackendItemController@update');
        Route::delete('{item}', 'BackendItemController@delete');
    });
});

Route::group([
    'prefix' => 'frontend'
], function () {
    Route::group([
        'prefix' => 'category'
    ], function () {
        Route::get('{vendorSlug}', 'FrontendCategoryController@index');
        Route::get('{vendorSlug}/{categorySlug}', 'FrontendCategoryController@show');
    });
    Route::group([
        'prefix' => 'item'
    ], function () {
        Route::get('{vendorName}', 'FrontendItemController@index');
        Route::get('{item}', 'FrontendItemController@show');
//        Route::get('{categorySlug}/{item}', 'FrontendItemController@show');
    });
});

Route::group([
    'prefix' => 'order'
], function () {
    Route::get('/index', 'OrderController@index');
    Route::get('/show', 'OrderController@show');
    Route::post('/store', 'OrderController@store');
});