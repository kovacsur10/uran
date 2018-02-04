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

Route::group(['middleware' => 'web', 'middleware' => 'auth.logged'], function () {
	// ECadmin routes
	Route::get('ecadmin/user/list', 'ECAdmin\UserController@show');
	Route::get('ecadmin/user/show/{userId}', 'ECAdmin\UserController@showUser');
	Route::post('ecadmin/user/show/{userId}', 'ECAdmin\UserController@updateUser');
	Route::get('ecadmin/maillist/list', 'ECAdmin\MailListController@showList');
	Route::post('ecadmin/maillist/show', 'ECAdmin\MailListController@show');
	Route::post('ecadmin/maillist/showdiff', 'ECAdmin\MailListController@showDiff');
	
	// Admin routes
	Route::get('admin/permissions', 'Admin\PermissionController@showPermissions');
	Route::post('admin/permissions', 'Admin\PermissionController@modifyPermissions');
	Route::post('admin/permissions/set', 'Admin\PermissionController@setPermissions');
	Route::post('admin/permissions/list', 'Admin\PermissionController@getUsersWithPermission');
	Route::get('admin/modules', 'Admin\ModuleController@show');
	Route::post('admin/modules/activate', 'Admin\ModuleController@activate');
	Route::post('admin/modules/deactivate', 'Admin\ModuleController@deactivate');
	Route::get('admin/registration/reject/{id}', 'Admin\RegistrationController@reject');
	Route::get('admin/registration/show', 'Admin\RegistrationController@showList');
	Route::get('admin/registration/show/{id}', 'Admin\RegistrationController@show');
	Route::post('admin/registration/accept', 'Admin\RegistrationController@accept');
	Route::get('admin/groups/list', 'Admin\GroupController@showGroups');
	Route::post('admin/groups/modify', 'Admin\GroupController@showModifyPage');
	Route::post('admin/groups/modify_values', 'Admin\GroupController@modify');
	Route::post('admin/groups/user', 'Admin\GroupController@showUserModificationPage');
	Route::post('admin/groups/user_modification', 'Admin\GroupController@modifyUser');
	Route::post('admin/groups/users', 'Admin\GroupController@getUsersWithGroup');
	
	// Login and logout routes
	Route::get('logout', 'Auth\AuthController@logout');

	// User data routes
	Route::get('data/show', 'User\UserController@showData');
	Route::get('data/{username}', 'User\UserController@showPublicData');
	Route::get('data/languageexam/uploaded/{location}', 'User\UserController@showLanguageExam');
	Route::get('data/languageexam/upload', 'User\UserController@getLanguageExams');
	Route::get('data/languageexam/upload/{examid}', 'User\UserController@showUploadLanguageExam');
	Route::put('data/languageexam/upload/{examid}', 'User\UserController@uploadLanguageExam');
	
	// ECNET routes
	Route::get('ecnet/account', 'Ecnet\PrintingController@showAccount')->middleware('modules.ecnet');
	Route::post('ecnet/addmoney', 'Ecnet\PrintingController@addMoney')->middleware('modules.ecnet');
	Route::post('ecnet/addfreepages', 'Ecnet\PrintingController@addFreePages')->middleware('modules.ecnet');
	
	Route::get('ecnet/access', 'Ecnet\AccessController@showInternet')->middleware('modules.ecnet');
	Route::post('ecnet/setvalidtime', 'Ecnet\AccessController@updateValidationTime')->middleware('modules.ecnet');
	Route::post('ecnet/activate', 'Ecnet\AccessController@activate')->middleware('modules.ecnet');
	Route::post('ecnet/setmacs', 'Ecnet\AccessController@setMACAddresses')->middleware('modules.ecnet');
	
	Route::get('ecnet/order', 'Ecnet\SlotController@showMACOrderForm')->middleware('modules.ecnet');
	Route::post('ecnet/allowordenyorder', 'Ecnet\SlotController@allowOrDenyOrder')->middleware('modules.ecnet');
	Route::post('ecnet/getslot', 'Ecnet\SlotController@getSlot')->middleware('modules.ecnet');
	
	Route::get('ecnet/users', 'Ecnet\AdminController@showUsers')->middleware('modules.ecnet');
	Route::get('ecnet/users/resetfilter', 'Ecnet\AdminController@resetFilterUsers')->middleware('modules.ecnet');
	Route::get('ecnet/users/listactives/{type}', 'Ecnet\AdminController@showActiveUsers')->middleware('modules.ecnet');
	Route::get('ecnet/users/{count}', 'Ecnet\AdminController@showUsers')->middleware('modules.ecnet');
	Route::get('ecnet/users/{count}/{first}', 'Ecnet\AdminController@showUsers')->middleware('modules.ecnet');
	Route::post('ecnet/users', 'Ecnet\AdminController@filterUsers')->middleware('modules.ecnet');
	
	// Rooms routes
	Route::get('rooms/map', 'Rooms\RoomsController@showDefaultMap')->middleware('modules.rooms');
	Route::get('rooms/map/{level}', 'Rooms\RoomsController@showMap')->middleware('modules.rooms');
	Route::get('rooms/room/{id}', 'Rooms\RoomsController@listRoomMembers')->middleware('modules.rooms');
	Route::get('rooms/download', 'Rooms\RoomsController@downloadList')->middleware('modules.rooms');
	Route::post('rooms/assign/{guard}', 'Rooms\RoomsController@assignResidents')->middleware('modules.rooms');
	Route::post('rooms/tables/select/{level}', 'Rooms\RoomsController@selectTable')->middleware('modules.rooms');
	Route::post('rooms/tables/add/{level}', 'Rooms\RoomsController@addTable')->middleware('modules.rooms');
	Route::post('rooms/tables/remove/{level}', 'Rooms\RoomsController@removeTable')->middleware('modules.rooms');
	
	// Notification routes
	Route::get('notification/list/{first}', 'Notification\NotificationController@listNotifications');
	Route::get('notification/show/{id}', 'Notification\NotificationController@showNotification');
	Route::get('notification/readall', 'Notification\NotificationController@readAll');
	
	// Tasks routes
	Route::get('tasks/list', 'Tasks\TaskController@show')->middleware('modules.tasks');
	Route::get('tasks/task/{id}', 'Tasks\TaskController@showTask')->middleware('modules.tasks');
	Route::post('tasks/task/{taskId}/modify', 'Tasks\TaskController@modify')->middleware('modules.tasks');
	Route::put('tasks/task/{taskId}/addcomment', 'Tasks\TaskController@addComment')->middleware('modules.tasks');
	Route::get('tasks/task/{taskId}/removecomment/{commentId}', 'Tasks\TaskController@removeComment')->middleware('modules.tasks');
	Route::get('tasks/new', 'Tasks\TaskController@add')->middleware('modules.tasks');
	Route::put('tasks/new', 'Tasks\TaskController@addNew')->middleware('modules.tasks');
	Route::get('tasks/task/{taskId}/remove', 'Tasks\TaskController@remove')->middleware('modules.tasks');
	Route::post('tasks/tasks', 'Tasks\TaskController@filterTasks')->middleware('modules.tasks');
	Route::get('tasks/resetfilter', 'Tasks\TaskController@resetFilterTasks')->middleware('modules.tasks');
	Route::get('tasks/tasks/{count}', 'Tasks\TaskController@show')->middleware('modules.tasks');
	Route::get('tasks/tasks/{count}/{first}', 'Tasks\TaskController@show')->middleware('modules.tasks');
	
	// ECouncil routes
	Route::get('ecouncil/records', 'ECouncil\RecordController@show')->middleware('modules.ecouncil');
	Route::put('ecouncil/records', 'ECouncil\RecordController@add')->middleware('modules.ecouncil');
	Route::get('ecouncil/records/view/{id}', 'ECouncil\RecordController@showRecord')->middleware('modules.ecouncil');
	Route::get('ecouncil/records/{count}', 'ECouncil\RecordController@show')->middleware('modules.ecnet');
	Route::get('ecouncil/records/{count}/{first}', 'ECouncil\RecordController@show')->middleware('modules.ecnet');
});

Route::group(['middleware' => 'web', 'middleware' => 'auth.notlogged'], function () {
	// Login and logout routes
	Route::get('login', 'Auth\AuthController@showLoginForm');
	Route::post('login', 'Auth\AuthController@login');

	// Registration Routes...
	Route::get('register', 'Auth\RegisterController@showRegistrationChooserForm');
	Route::get('register/member', 'Auth\RegisterController@showCollegistRegistrationForm');
	Route::get('register/guest', 'Auth\RegisterController@showGuestRegistrationForm');
	Route::get('register/{code}', 'Auth\RegisterController@verify');
	Route::put('register/member', 'Auth\RegisterController@registerCollegist');
	Route::put('register/guest', 'Auth\RegisterController@registerGuest');

	// Password Reset Routes...
	Route::get('password/reset', 'Auth\PasswordController@showResetForm');
	Route::get('password/reset/{username}/{code}', 'Auth\PasswordController@showPasswordForm');
	Route::post('password/reset', 'Auth\PasswordController@reset');
	Route::post('password/email', 'Auth\PasswordController@completeReset');
});

Route::group(['middleware' => 'web'], function () {
	// Language routes
	Route::get('lang/set/{language}', 'Language\LanguageController@set');

	// Basic routes
	Route::get('/', 'HomeController@index');

	Route::get('home', 'HomeController@index');
});