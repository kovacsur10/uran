<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'web'], function () {
	// Login and logout routes
	Route::get('login', 'Auth\AuthController@showLoginForm')->middleware('auth.notlogged');
	Route::post('login', 'Auth\AuthController@login')->middleware('auth.notlogged');
	Route::get('logout', 'Auth\AuthController@logout')->middleware('auth.logged');

	// Registration Routes...
	Route::get('register', 'Auth\AuthController@showRegistrationForm')->middleware('auth.notlogged');
	Route::get('register/{code}', 'Auth\AuthController@vefify')->middleware('auth.notlogged');
	Route::put('register', 'Auth\AuthController@register')->middleware('auth.notlogged');

	// Password Reset Routes...
	Route::get('password/reset', 'Auth\PasswordController@showResetForm')->middleware('auth.notlogged');
	Route::get('password/reset/{username}/{code}', 'Auth\PasswordController@showPasswordForm')->middleware('auth.notlogged');
	Route::post('password/reset', 'Auth\PasswordController@reset')->middleware('auth.notlogged');
	Route::post('password/email', 'Auth\PasswordController@completeReset')->middleware('auth.notlogged');

	// User data routes
	Route::get('data/show', 'User\UserController@showData')->middleware('auth.logged');
	
	// Eir routes
	Route::get('ecnet/account', 'Eir\EirController@showAccount')->middleware('auth.logged');
	Route::get('ecnet/access', 'Eir\EirController@showInternet')->middleware('auth.logged');
	Route::get('ecnet/order', 'Eir\EirController@showMACOrderForm')->middleware('auth.logged');
	Route::get('ecnet/users', 'Eir\EirController@showUsers')->middleware('auth.logged');
	Route::post('ecnet/addmoney', 'Eir\EirController@addMoney')->middleware('auth.logged');
	Route::post('ecnet/setvalidtime', 'Eir\EirController@updateValidationTime')->middleware('auth.logged');
	Route::post('ecnet/activate', 'Eir\EirController@activate')->middleware('auth.logged');
	Route::post('ecnet/setmacs', 'Eir\EirController@setMACAddresses')->middleware('auth.logged');
	Route::post('ecnet/getslot', 'Eir\EirController@getSlot')->middleware('auth.logged');
	Route::post('ecnet/allowordenyorder', 'Eir\EirController@allowOrDenyOrder')->middleware('auth.logged');
	
	// Basic routes
    Route::get('/', 'HomeController@index');

    Route::get('/home', 'HomeController@index');
});
