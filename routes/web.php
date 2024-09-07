<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserImportController;

use App\Http\Controllers\MessageController; 
use App\Http\Controllers\ChatController;
use App\Events\MessageSent;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

Route::impersonate();

// Auth::routes();
// Authentication Routes...
Route::group(['prefix' => 'admin'], function () {
Route::get('login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
Route::post('logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'App\Http\Controllers\Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'App\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.update');

// Confirm Password (added in v6.2)
Route::get('password/confirm', 'App\Http\Controllers\Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'App\Http\Controllers\Auth\ConfirmPasswordController@confirm');

// Email Verification Routes...
Route::get('email/verify', 'App\Http\Controllers\Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify'); // v6.x
/* Route::get('email/verify/{id}', 'App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify'); // v5.x */
Route::get('email/resend', 'App\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');





Route::get('/', 'App\Http\Controllers\HomeController@index');
Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::get('/config', 'App\Http\Controllers\ConfigController@index')->name('config');
Route::put('/config/update/{id}', 'App\Http\Controllers\ConfigController@update')->name('config.update');
Route::post('/config/store/permission_group', 'App\Http\Controllers\ConfigController@storePermissionGroup')->name('config.store.permission_group');
Route::put('/config/update/permission_group/{id}', 'App\Http\Controllers\ConfigController@updatePermissionGroup')->name('config.update.permission_group');
Route::post('/config/store/permission', 'App\Http\Controllers\ConfigController@storePermission')->name('config.store.permission');
Route::put('/config/update/permission/{id}', 'App\Http\Controllers\ConfigController@updatePermission')->name('config.update.permission');


Route::group(['namespace' => 'App\Http\Controllers\Page'], function (){ 
	Route::get('/page', 'PageController@page')->name('page');
	Route::get('/page/create', 'PageController@create')->name('page.create');
	Route::get('/page/destroy/{id}', 'PageController@destroy')->name('page.destroy');
	Route::put('/page/update/{id}', 'PageController@update')->name('page.update');
	Route::get('/page/show/{id}', 'PageController@show')->name('page.show');
	Route::get('/page/edit/{id}', 'PageController@edit')->name('page.edit');
	Route::post('/page/store', 'PageController@store')->name('page.store');
});

Route::group(['namespace' => 'App\Http\Controllers\Profile'], function (){ 
	Route::get('/profile', 'ProfileController@index')->name('profile');
	Route::put('/profile/update/profile/{id}', 'ProfileController@updateProfile')->name('profile.update.profile');
	Route::put('/profile/update/password/{id}', 'ProfileController@updatePassword')->name('profile.update.password');
	Route::put('/profile/update/avatar/{id}', 'ProfileController@updateAvatar')->name('profile.update.avatar');
});

Route::group(['namespace' => 'App\Http\Controllers\Error'], function (){ 
	Route::get('/unauthorized', 'ErrorController@unauthorized')->name('unauthorized');
});

Route::group(['namespace' => 'App\Http\Controllers\User'], function (){ 
	//Users
	Route::get('/user', 'UserController@index')->name('user');
	Route::get('/user/create', 'UserController@create')->name('user.create');
	Route::post('/user/store', 'UserController@store')->name('user.store');
	Route::get('/user/edit/{id}', 'UserController@edit')->name('user.edit');
	Route::put('/user/update/{id}', 'UserController@update')->name('user.update');
	Route::get('/user/edit/password/{id}', 'UserController@editPassword')->name('user.edit.password');
	Route::put('/user/update/password/{id}', 'UserController@updatePassword')->name('user.update.password');
	Route::get('/user/show/{id}', 'UserController@show')->name('user.show');
	Route::get('/user/destroy/{id}', 'UserController@destroy')->name('user.destroy');
	// Roles
	Route::get('/role', 'RoleController@index')->name('role');
	Route::get('/role/create', 'RoleController@create')->name('role.create');
	Route::post('/role/store', 'RoleController@store')->name('role.store');
	Route::get('/role/edit/{id}', 'RoleController@edit')->name('role.edit');
	Route::put('/role/update/{id}', 'RoleController@update')->name('role.update');
	Route::get('/role/show/{id}', 'RoleController@show')->name('role.show');
	Route::get('/role/destroy/{id}', 'RoleController@destroy')->name('role.destroy');
});

Route::get('/login/google', 'App\Http\Controllers\Auth\LoginController@redirectToGoogle')->name('login.google');
Route::get('/login/google/callback', 'App\Http\Controllers\Auth\LoginController@handleGoogleCallback');
//Facebook
Route::get('/login/facebook', 'App\Http\Controllers\Auth\LoginController@redirectToFacebook')->name('login.facebook');
Route::get('/login/facebook/callback', 'App\Http\Controllers\Auth\LoginController@handleFacebookCallback');
//Github
Route::get('/login/github', 'App\Http\Controllers\Auth\LoginController@redirectToGithub')->name('login.github');
Route::get('/login/github/callback', 'App\Http\Controllers\Auth\LoginController@handleGithubCallback');
Route::get('lang/{locale}', function ($locale) {
	//dd($locale);
    if (in_array($locale, ['en', 'pt_BR'])) {
        session(['locale' => $locale]);
        App::setLocale($locale);
    }
	//dd(App::getLocale());
    // return view('home');
	return redirect()->back();
});
Route::get('/export-users', function () {
    return Excel::download(new UsersExport, 'users.csv');
});
Route::get('/import-users', [UserImportController::class, 'showImportForm'])->name('import.form');
Route::post('/import-users', [UserImportController::class, 'importUsers'])->name('import.users');



// Route to serve the chat view
Route::get('/chat', function () {
    return view('chat');
})->name('chat');

// Route to get messages (optional if you want to fetch messages on load)
Route::get('/messages', [ChatController::class, 'getMessages']);



Route::get('/message/index', [MessageController::class, 'index']);
Route::get('/message/send', [MessageController::class, 'send']);


Route::get('/message-private/index', [MessageController::class, 'p_index']);
Route::get('/message-private/send', [MessageController::class, 'p_send']);

});

Route::get('/', 'App\Http\Controllers\SiteController@index');
Route::get('/page/{id}', 'App\Http\Controllers\SiteController@page')->name('sitepage.show');