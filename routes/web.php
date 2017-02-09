<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['middleware' => 'web'], function () {
	// Language router
	Route::get('lang/set/{language}', 'Language\LanguageController@set');
	
	// ECadmin routes
	Route::get('ecadmin/user/list', 'ECAdmin\UserController@show')->middleware('auth.logged');
	Route::get('ecadmin/user/show/{userId}', 'ECAdmin\UserController@showUser')->middleware('auth.logged');
	Route::post('ecadmin/user/show/{userId}', 'ECAdmin\UserController@updateUser')->middleware('auth.logged');
	
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
	Route::get('register', 'Auth\RegisterController@showRegistrationChooserForm')->middleware('auth.notlogged');
	Route::get('register/collegist', 'Auth\RegisterController@showCollegistRegistrationForm')->middleware('auth.notlogged');
	Route::get('register/guest', 'Auth\RegisterController@showGuestRegistrationForm')->middleware('auth.notlogged');
	Route::get('register/{code}', 'Auth\RegisterController@verify')->middleware('auth.notlogged');
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
	Route::get('ecnet/account', 'Ecnet\PrintingController@showAccount')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/addmoney', 'Ecnet\PrintingController@addMoney')->middleware('auth.logged')->middleware('modules.ecnet');
	
	Route::get('ecnet/access', 'Ecnet\AccessController@showInternet')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/setvalidtime', 'Ecnet\AccessController@updateValidationTime')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/activate', 'Ecnet\AccessController@activate')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/setmacs', 'Ecnet\AccessController@setMACAddresses')->middleware('auth.logged')->middleware('modules.ecnet');
	
	Route::get('ecnet/order', 'Ecnet\SlotController@showMACOrderForm')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/allowordenyorder', 'Ecnet\SlotController@allowOrDenyOrder')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/getslot', 'Ecnet\SlotController@getSlot')->middleware('auth.logged')->middleware('modules.ecnet');
	
	Route::get('ecnet/users', 'Ecnet\AdminController@showUsers')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::get('ecnet/users/resetfilter', 'Ecnet\AdminController@resetFilterUsers')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::get('ecnet/users/listactives/{type}', 'Ecnet\AdminController@showActiveUsers')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::get('ecnet/users/{count}', 'Ecnet\AdminController@showUsers')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::get('ecnet/users/{count}/{first}', 'Ecnet\AdminController@showUsers')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::post('ecnet/users', 'Ecnet\AdminController@filterUsers')->middleware('auth.logged')->middleware('modules.ecnet');
	
	// Rooms routes
	Route::get('rooms/map/{level}', 'Rooms\RoomsController@showMap')->middleware('auth.logged')->middleware('modules.rooms');
	Route::get('rooms/room/{id}', 'Rooms\RoomsController@listRoomMembers')->middleware('auth.logged')->middleware('modules.rooms');
	Route::get('rooms/download', 'Rooms\RoomsController@downloadList')->middleware('auth.logged')->middleware('modules.rooms');
	Route::post('rooms/assign/{guard}', 'Rooms\RoomsController@assignResidents')->middleware('auth.logged')->middleware('modules.rooms');
	Route::post('rooms/tables/select/{level}', 'Rooms\RoomsController@selectTable')->middleware('auth.logged')->middleware('modules.rooms');
	Route::post('rooms/tables/add/{level}', 'Rooms\RoomsController@addTable')->middleware('auth.logged')->middleware('modules.rooms');
	Route::post('rooms/tables/remove/{level}', 'Rooms\RoomsController@removeTable')->middleware('auth.logged')->middleware('modules.rooms');
	
	// Notification routes
	Route::get('notification/list/{first}', 'Notification\NotificationController@listNotifications')->middleware('auth.logged');
	Route::get('notification/show/{id}', 'Notification\NotificationController@showNotification')->middleware('auth.logged');
	
	// Tasks routes
	Route::get('tasks/list', 'Tasks\TaskController@show')->middleware('auth.logged')->middleware('modules.tasks');
	Route::get('tasks/task/{id}', 'Tasks\TaskController@showTask')->middleware('auth.logged')->middleware('modules.tasks');
	Route::post('tasks/task/{taskId}/modify', 'Tasks\TaskController@modify')->middleware('auth.logged')->middleware('modules.tasks');
	Route::put('tasks/task/{taskId}/addcomment', 'Tasks\TaskController@addComment')->middleware('auth.logged')->middleware('modules.tasks');
	Route::get('tasks/task/{taskId}/removecomment/{commentId}', 'Tasks\TaskController@removeComment')->middleware('auth.logged')->middleware('modules.tasks');
	Route::get('tasks/new', 'Tasks\TaskController@add')->middleware('auth.logged')->middleware('modules.tasks');
	Route::put('tasks/new', 'Tasks\TaskController@addNew')->middleware('auth.logged')->middleware('modules.tasks');
	Route::get('tasks/task/{taskId}/remove', 'Tasks\TaskController@remove')->middleware('auth.logged')->middleware('modules.tasks');
	Route::post('tasks/tasks', 'Tasks\TaskController@filterTasks')->middleware('auth.logged')->middleware('modules.tasks');
	Route::get('tasks/resetfilter', 'Tasks\TaskController@resetFilterTasks')->middleware('auth.logged')->middleware('modules.tasks');
	Route::get('tasks/tasks/{count}', 'Tasks\TaskController@show')->middleware('auth.logged')->middleware('modules.ecnet');
	Route::get('tasks/tasks/{count}/{first}', 'Tasks\TaskController@show')->middleware('auth.logged')->middleware('modules.ecnet');
	
	// ECouncil routes
	Route::get('ecouncil/records/list', 'ECouncil\RecordController@show')->middleware('auth.logged')->middleware('modules.ecouncil');
	
	// Basic routes
    Route::get('/', 'HomeController@index');

    Route::get('home', 'HomeController@index');
});