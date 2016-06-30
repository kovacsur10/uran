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
	// Language router
	Route::get('lang/set/{language}', 'Language\LanguageController@set');
	
	// Admin routes
	Route::get('admin/permissions', 'Admin\PermissionController@showPermissions')->middleware('auth.logged');
	Route::post('admin/permissions', 'Admin\PermissionController@modifyPermissions')->middleware('auth.logged');
	Route::post('admin/permissions/set', 'Admin\PermissionController@setPermissions')->middleware('auth.logged');
	Route::post('admin/permissions/list', 'Admin\PermissionController@getUsersWithPermission')->middleware('auth.logged');
	Route::get('admin/modules', 'Admin\ModuleController@show')->middleware('auth.logged');
	Route::post('admin/modules/activate', 'Admin\ModuleController@activate')->middleware('auth.logged');
	Route::post('admin/modules/deactivate', 'Admin\ModuleController@deactivate')->middleware('auth.logged');
	Route::get('admin/registration/reject/{id}', 'Admin\RegistrationController@reject')->middleware('auth.logged');
	Route::get('admin/registration/show', 'Admin\RegistrationController@showList')->middleware('auth.logged');
	Route::get('admin/registration/show/{id}', 'Admin\RegistrationController@show')->middleware('auth.logged');
	Route::post('admin/registration/accept', 'Admin\RegistrationController@accept')->middleware('auth.logged');
	
	// Login and logout routes
	Route::get('login', 'Auth\AuthController@showLoginForm')->middleware('auth.notlogged');
	Route::post('login', 'Auth\AuthController@login')->middleware('auth.notlogged');
	Route::get('logout', 'Auth\AuthController@logout')->middleware('auth.logged');

	// Registration Routes...
	Route::get('register', 'Auth\RegisterController@showRegistrationChoserForm')->middleware('auth.notlogged');
	Route::get('register/collegist', 'Auth\RegisterController@showCollegistRegistrationForm')->middleware('auth.notlogged');
	Route::get('register/guest', 'Auth\RegisterController@showGuestRegistrationForm')->middleware('auth.notlogged');
	Route::get('register/{code}', 'Auth\RegisterController@vefify')->middleware('auth.notlogged');
	Route::put('register/collegist', 'Auth\RegisterController@registerCollegist')->middleware('auth.notlogged');
	Route::put('register/guest', 'Auth\RegisterController@registerGuest')->middleware('auth.notlogged');

	// Password Reset Routes...
	Route::get('password/reset', 'Auth\PasswordController@showResetForm')->middleware('auth.notlogged');
	Route::get('password/reset/{username}/{code}', 'Auth\PasswordController@showPasswordForm')->middleware('auth.notlogged');
	Route::post('password/reset', 'Auth\PasswordController@reset')->middleware('auth.notlogged');
	Route::post('password/email', 'Auth\PasswordController@completeReset')->middleware('auth.notlogged');

	// User data routes
	Route::get('data/show', 'User\UserController@showData')->middleware('auth.logged');
	Route::get('data/{username}', 'User\UserController@showPublicData')->middleware('auth.logged');
	
	// ECNET routes
	Route::get('ecnet/account', 'Ecnet\EcnetController@showAccount')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::get('ecnet/access', 'Ecnet\EcnetController@showInternet')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::get('ecnet/order', 'Ecnet\EcnetController@showMACOrderForm')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::get('ecnet/users', 'Ecnet\EcnetController@showUsers')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::get('ecnet/users/resetfilter', 'Ecnet\EcnetController@resetFilterUsers')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::get('ecnet/users/listactives/{type}', 'Ecnet\EcnetController@showActiveUsers')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::get('ecnet/users/{count}', 'Ecnet\EcnetController@showUsers')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::get('ecnet/users/{count}/{first}', 'Ecnet\EcnetController@showUsers')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/users', 'Ecnet\EcnetController@filterUsers')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/addmoney', 'Ecnet\EcnetController@addMoney')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/setvalidtime', 'Ecnet\EcnetController@updateValidationTime')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/activate', 'Ecnet\EcnetController@activate')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/setmacs', 'Ecnet\EcnetController@setMACAddresses')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/getslot', 'Ecnet\EcnetController@getSlot')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/allowordenyorder', 'Ecnet\EcnetController@allowOrDenyOrder')->middleware('auth.logged')->middleware('modules.ecnet');
	
	// Rooms routes
	Route::get('rooms/map/{level}', 'Rooms\RoomsController@showMap')->middleware('auth.logged')->middleware('modules.rooms');
	Route::get('rooms/room/{id}', 'Rooms\RoomsController@listRoomMembers')->middleware('auth.logged')->middleware('modules.rooms');
	Route::post('rooms/assign', 'Rooms\RoomsController@assignResidents')->middleware('auth.logged')->middleware('modules.rooms');
	
	// Notification routes
	Route::get('notification/list/{first}', 'Notification\NotificationController@listNotifications')->middleware('auth.logged');
	Route::get('notification/show/{id}', 'Notification\NotificationController@showNotification')->middleware('auth.logged');
	
	// Basic routes
    Route::get('/', 'HomeController@index');

    Route::get('home', 'HomeController@index');
});
