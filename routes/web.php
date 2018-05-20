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

Route::get('/', function () {
    return redirect()->to('/home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/user', 'UserController@index');
Route::get('/auth/logout', 'UserController@getLogOut');
Route::get('/auth/jaccount', 'JaccountController@auth');

Route::prefix("train")->group(function () {
    Route::get('/leftTicket', 'TrainController@index');
    Route::get('/detail', 'TrainController@detail');
});

Route::prefix("order")->group(function () {
    Route::get('/', 'OrderController@allOrder');
    Route::post('/add', 'OrderController@addOrder');
    Route::get('/pay/{order_id}', 'OrderController@payOrder');
    Route::get('/pay/confirm/{order_id}', 'OrderController@confirmPayOrder');
    Route::get('/cancel/{order_id}', 'OrderController@cancelOrder');
});

Route::prefix("user")->group(function () {
    Route::get('/balance', 'BalanceController@showBalance');
});

// APIs
Route::prefix("api/user")->group(function () {
    Route::get('/info', "ApiController@apiUserInfo");
    Route::get('/isLogin', 'ApiController@apiUserLogon');
});

Route::prefix("api/train")->group(function () {
    Route::post('/leftTicket', "ApiController@apiLeftTicket");
});

Route::prefix("api/admin")->group(function () {
    Route::post('/login', 'UserController@apiAdminLogin');
    Route::get('/users/all', 'AdminApiController@apiUsersAll');
    Route::get('/refund/{refund_id}/confirm', 'AdminApiController@apiReviewRefund');
    Route::get('/refund/get', 'AdminApiController@apiRefundGet');
    Route::get('/orders/all', 'AdminApiController@apiOrdersAll');
    Route::get('/users/{user_id}/orders/all', 'AdminApiController@apiUsersOrdersAll');
});

Route::prefix("api/stations")->group(function () {
    Route::get('/search', 'ApiController@apiStationsSearch');
});

Route::prefix("user")->group(function() {
    Route::get('/account', 'UserController@account');
});