<?php

use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->namespace('Backend')->middleware('auth:admin_user')->group(function(){
		Route::get('/', 'PageController@home')->name('home');
		
		Route::resource('admin-user', 'AdminUserController');

		// server side data(ssd) datatable Route
		Route::get('admin-user/datatable/ssd', 'AdminUserController@ssd');
});



