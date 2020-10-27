<?php

// Home
Route::namespace('Home')->group(function() {

	// Get home
	Route::post('/', 'HomeController@home');

	// Get dashboard
	Route::post('info', 'HomeController@info');

});

// Deals
Route::namespace('Deals')->prefix('deals')->group(function() {

	// Deals
	Route::get('/', 'DealsController@deals');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Create
		Route::post('create/fetch', 'CreateController@fetch');
		Route::post('create', 'CreateController@create');

		// Edit
		Route::get('edit/{id}', 'EditController@edit');
		Route::post('update', 'EditController@update');

		// Statistics
		Route::post('statistics', 'StatisticsController@statistics');
		Route::get('statistics/more/{id}', 'StatisticsController@more');

		// Delete
		Route::post('delete', 'DeleteController@delete');

		// Restore
		//Route::post('restore', 'RestoreController@restore');

		// Archive
		Route::post('archive', 'ArchiveController@archive');

		// Statistics
		Route::post('statistics', 'StatisticsController@statistics');
		Route::get('statistics/more/{id}', 'StatisticsController@more');

	});

});

// Shops
Route::namespace('Shops')->prefix('shops')->group(function() {

	// Shops
	Route::get('/', 'ShopsController@shops');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Create
		Route::post('create/fetch', 'CreateController@fetch');
		Route::post('create', 'CreateController@create');

		// Edit
		Route::post('edit', 'EditController@edit');
		Route::post('update', 'EditController@update');

		// Delete
		Route::post('delete', 'DeleteController@delete');

		// Restore
		Route::post('restore', 'RestoreController@restore');

	});

});

// Messages
Route::namespace('Messages')->prefix('messages')->group(function() {

	// Messages
	Route::get('/', 'MessagesController@messages');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Read
		Route::post('read', 'ReadController@read');

		// Reply
		Route::post('reply', 'ReplyController@reply');

	});

});

// Followers
Route::namespace('Followers')->prefix('followers')->group(function() {

	// Followers
	Route::get('/', 'FollowersController@followers');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// 

	});

});

// Settings
Route::namespace('Settings')->prefix('settings')->group(function() {

	// General
	Route::post('general', 'GeneralController@settings');
	Route::post('general/update', 'GeneralController@update');

	// Watermark
	Route::get('watermark', 'WatermarkController@settings');
	Route::post('watermark', 'WatermarkController@update');

	// Autoshare
	Route::get('autoshare', 'AutoshareController@settings');
	Route::post('autoshare', 'AutoshareController@update');

	// Subscription
	Route::get('subscription', 'SubscriptionController@subscription');
	Route::post('subscription', 'SubscriptionController@update');

});

// Fetch
Route::namespace('Fetch')->prefix('ajax/fetch')->group(function() {

	// Timezones
	Route::post('timezones', 'TimezonesController@list');

});