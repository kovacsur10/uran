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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::group(['middleware' => 'web'], function () {
	// Language router
	Route::get('lang/set/{language}', 'Language\LanguageController@set');//DONE
	
	// ECadmin routes
	Route::get('ecadmin/user/list', 'ECAdmin\UserController@show')->middleware('auth.logged');//DONE
	Route::get('ecadmin/user/show/{userId}', 'ECAdmin\UserController@showUser')->middleware('auth.logged');//TODO
	Route::post('ecadmin/user/show/{userId}', 'ECAdmin\UserController@updateUser')->middleware('auth.logged');//TODO
	
	// Admin routes
	Route::get('admin/permissions', 'Admin\PermissionController@showPermissions')->middleware('auth.logged');//TODO
	Route::post('admin/permissions', 'Admin\PermissionController@modifyPermissions')->middleware('auth.logged');//TODO
	Route::post('admin/permissions/set', 'Admin\PermissionController@setPermissions')->middleware('auth.logged');//TODO
	Route::post('admin/permissions/list', 'Admin\PermissionController@getUsersWithPermission')->middleware('auth.logged');//TODO
	Route::get('admin/modules', 'Admin\ModuleController@show')->middleware('auth.logged');//TODO
	Route::post('admin/modules/activate', 'Admin\ModuleController@activate')->middleware('auth.logged');//TODO
	Route::post('admin/modules/deactivate', 'Admin\ModuleController@deactivate')->middleware('auth.logged');//TODO
	Route::get('admin/registration/reject/{id}', 'Admin\RegistrationController@reject')->middleware('auth.logged');//TODO
	Route::get('admin/registration/show', 'Admin\RegistrationController@showList')->middleware('auth.logged');//TODO
	Route::get('admin/registration/show/{id}', 'Admin\RegistrationController@show')->middleware('auth.logged');//TODO
	Route::post('admin/registration/accept', 'Admin\RegistrationController@accept')->middleware('auth.logged');//TODO
	Route::get('admin/permissions/default', 'Admin\DefaultPermissionsController@show')->middleware('auth.logged');//TODO
	Route::post('admin/permissions/default/collegist', 'Admin\DefaultPermissionsController@setCollegist')->middleware('auth.logged');//TODO
	Route::post('admin/permissions/default/guest', 'Admin\DefaultPermissionsController@setGuest')->middleware('auth.logged');//TODO
	
	// Login and logout routes
	Route::get('login', 'Auth\AuthController@showLoginForm')->middleware('auth.notlogged');//DONE
	Route::post('login', 'Auth\AuthController@login')->middleware('auth.notlogged');//DONE
	Route::get('logout', 'Auth\AuthController@logout')->middleware('auth.logged');//DONE

	// Registration Routes...
	Route::get('register', 'Auth\RegisterController@showRegistrationChooserForm')->middleware('auth.notlogged');//DONE
	Route::get('register/collegist', 'Auth\RegisterController@showCollegistRegistrationForm')->middleware('auth.notlogged');//DONE
	Route::get('register/guest', 'Auth\RegisterController@showGuestRegistrationForm')->middleware('auth.notlogged');//DONE
	Route::get('register/{code}', 'Auth\RegisterController@verify')->middleware('auth.notlogged');//DONE
	Route::put('register/collegist', 'Auth\RegisterController@registerCollegist')->middleware('auth.notlogged');//DONE
	Route::put('register/guest', 'Auth\RegisterController@registerGuest')->middleware('auth.notlogged');//DONE

	// Password Reset Routes...
	Route::get('password/reset', 'Auth\PasswordController@showResetForm')->middleware('auth.notlogged');//DONE
	Route::get('password/reset/{username}/{code}', 'Auth\PasswordController@showPasswordForm')->middleware('auth.notlogged');//DONE
	Route::post('password/reset', 'Auth\PasswordController@reset')->middleware('auth.notlogged');//DONE
	Route::post('password/email', 'Auth\PasswordController@completeReset')->middleware('auth.notlogged');//DONE

	// User data routes
	Route::get('data/show', 'User\UserController@showData')->middleware('auth.logged');//DONE
	Route::get('data/{username}', 'User\UserController@showPublicData')->middleware('auth.logged');//DONE
	
	// ECNET routes
	Route::get('ecnet/account', 'Ecnet\PrintingController@showAccount')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	Route::post('ecnet/addmoney', 'Ecnet\PrintingController@addMoney')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	
	Route::get('ecnet/access', 'Ecnet\AccessController@showInternet')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	Route::post('ecnet/setvalidtime', 'Ecnet\AccessController@updateValidationTime')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	Route::post('ecnet/activate', 'Ecnet\AccessController@activate')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	Route::post('ecnet/setmacs', 'Ecnet\AccessController@setMACAddresses')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	
	Route::get('ecnet/order', 'Ecnet\SlotController@showMACOrderForm')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	Route::post('ecnet/allowordenyorder', 'Ecnet\SlotController@allowOrDenyOrder')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	Route::post('ecnet/getslot', 'Ecnet\SlotController@getSlot')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	
	Route::get('ecnet/users', 'Ecnet\AdminController@showUsers')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	Route::get('ecnet/users/resetfilter', 'Ecnet\AdminController@resetFilterUsers')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	Route::get('ecnet/users/listactives/{type}', 'Ecnet\AdminController@showActiveUsers')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	Route::get('ecnet/users/{count}', 'Ecnet\AdminController@showUsers')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	Route::get('ecnet/users/{count}/{first}', 'Ecnet\AdminController@showUsers')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	Route::post('ecnet/users', 'Ecnet\AdminController@filterUsers')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	
	// Rooms routes
	Route::get('rooms/map/{level}', 'Rooms\RoomsController@showMap')->middleware('auth.logged')->middleware('modules.rooms');//DONE
	Route::get('rooms/room/{id}', 'Rooms\RoomsController@listRoomMembers')->middleware('auth.logged')->middleware('modules.rooms');//DONE
	Route::get('rooms/download', 'Rooms\RoomsController@downloadList')->middleware('auth.logged')->middleware('modules.rooms');//DONE
	Route::post('rooms/assign/{guard}', 'Rooms\RoomsController@assignResidents')->middleware('auth.logged')->middleware('modules.rooms');//DONE
	Route::post('rooms/tables/select/{level}', 'Rooms\RoomsController@selectTable')->middleware('auth.logged')->middleware('modules.rooms');//DONE
	Route::post('rooms/tables/add/{level}', 'Rooms\RoomsController@addTable')->middleware('auth.logged')->middleware('modules.rooms');//DONE
	Route::post('rooms/tables/remove/{level}', 'Rooms\RoomsController@removeTable')->middleware('auth.logged')->middleware('modules.rooms');//DONE
	
	// Notification routes
	Route::get('notification/list/{first}', 'Notification\NotificationController@listNotifications')->middleware('auth.logged');//DONE
	Route::get('notification/show/{id}', 'Notification\NotificationController@showNotification')->middleware('auth.logged');//DONE
	
	// Tasks routes
	Route::get('tasks/list', 'Tasks\TaskController@show')->middleware('auth.logged')->middleware('modules.tasks');//DONE
	Route::get('tasks/task/{id}', 'Tasks\TaskController@showTask')->middleware('auth.logged')->middleware('modules.tasks');//DONE
	Route::post('tasks/task/{taskId}/modify', 'Tasks\TaskController@modify')->middleware('auth.logged')->middleware('modules.tasks');//DONE
	Route::put('tasks/task/{taskId}/addcomment', 'Tasks\TaskController@addComment')->middleware('auth.logged')->middleware('modules.tasks');//DONE
	Route::get('tasks/task/{taskId}/removecomment/{commentId}', 'Tasks\TaskController@removeComment')->middleware('auth.logged')->middleware('modules.tasks');//DONE
	Route::get('tasks/new', 'Tasks\TaskController@add')->middleware('auth.logged')->middleware('modules.tasks');//DONE
	Route::put('tasks/new', 'Tasks\TaskController@addNew')->middleware('auth.logged')->middleware('modules.tasks');//DONE
	Route::get('tasks/task/{taskId}/remove', 'Tasks\TaskController@remove')->middleware('auth.logged')->middleware('modules.tasks');//DONE
	Route::post('tasks/tasks', 'Tasks\TaskController@filterTasks')->middleware('auth.logged')->middleware('modules.tasks');//DONE
	Route::get('tasks/resetfilter', 'Tasks\TaskController@resetFilterTasks')->middleware('auth.logged')->middleware('modules.tasks');//DONE
	Route::get('tasks/tasks/{count}', 'Tasks\TaskController@show')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	Route::get('tasks/tasks/{count}/{first}', 'Tasks\TaskController@show')->middleware('auth.logged')->middleware('modules.ecnet');//DONE
	
	// ECouncil routes
	Route::get('ecouncil/records/list', 'ECouncil\RecordController@show')->middleware('auth.logged')->middleware('modules.ecouncil');//DONE
	
	// Basic routes
    Route::get('/', 'HomeController@index');//DONE

    Route::get('home', 'HomeController@index');//DONE
});