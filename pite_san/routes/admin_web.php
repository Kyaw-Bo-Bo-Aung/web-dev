<?php

use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->namespace('Backend')->middleware('auth:admin_user')->group(function(){
		Route::get('/', 'PageController@home')->name('home');
		Route::resource('admin-user', 'AdminUserController');
		Route::resource('user', 'UserController');
		// server side data(ssd) datatable Route
		Route::get('admin-user/datatable/ssd', 'AdminUserController@ssd');
		Route::get('user/datatable/ssd', 'UserController@ssd');

		Route::get('wallet', 'WalletController@index')->name('wallet.index');
		Route::get('wallet/datatable/ssd', 'WalletController@ssd');
		// add wallet
		Route::get('wallet/add', 'WalletController@addWalletForm')->name('wallet.add.create');
		Route::post('wallet/add', 'WalletController@addWallet')->name('wallet.add.post');
		// reduce wallet
		Route::get('wallet/reduce', 'WalletController@reduceWalletForm')->name('wallet.reduce.create');
		Route::post('wallet/reduce', 'WalletController@reduceWallet')->name('wallet.reduce.post');
		// check password for add/reduce amount
		Route::post('add-wallet/password-check/{password?}', 'WalletController@checkPassword');

});



