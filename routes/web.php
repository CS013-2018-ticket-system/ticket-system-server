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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/user', 'UserController@index');

Route::get('/auth/jaccount', 'JaccountController@auth');

Route::prefix("train")->group(function () {
    Route::get('/leftTicket', 'TrainController@index');
    Route::get('/detail', 'TrainController@detail');
});

Route::prefix("order")->group(function () {
    Route::post('/add', 'OrderController@addOrder');
    Route::get('/pay/{order_id}', 'OrderController@payOrder');
    Route::get('/pay/confirm/{order_id}', 'OrderController@confirmPayOrder');
});

// APIs
Route::prefix("api/user")->group(function () {
    Route::get('/info', "ApiController@apiUserInfo");
});

Route::prefix("api/train")->group(function () {
    Route::post('/leftTicket', "ApiController@apiLeftTicket");
});