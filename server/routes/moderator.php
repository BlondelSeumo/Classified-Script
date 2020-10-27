<?php

// Home
Route::namespace('Home')->group(function() {

	// Home
	Route::post('/', 'HomeController@home');

});

// Profile
Route::namespace('Profile')->group(function() {

	// Update profile
	Route::post('profile', 'ProfileController@profile');

});

// Pending
Route::namespace('Pending')->prefix('pending')->group(function() {

	// Deals
	Route::get('deals', 'DealsController@deals');

	// Shops
	Route::get('shops', 'ShopsController@shops');

	// Comments
	Route::get('comments', 'CommentsController@comments');

	// Reports
	Route::get('reports', 'ReportsController@reports');

	// Mark report as read
	Route::post('reports/mark', 'ReportsController@mark');

});

// Deals
Route::namespace('Deals')->prefix('deals')->group(function() {

	// Deals
	Route::get('/', 'DealsController@deals');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Edit
		Route::get('edit/{id}', 'EditController@edit');
		Route::post('update', 'EditController@update');

		// Activate
		Route::post('activate', 'ActivateController@activate');

		// Arhive
		Route::post('archive', 'ArchiveController@archive');

		// Delete
		Route::post('delete', 'DeleteController@delete');

		// Restore
		Route::post('restore', 'RestoreController@restore');

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

		// Edit
		Route::post('edit', 'EditController@edit');
		Route::post('update', 'EditController@update');

		// Activate
		Route::post('activate', 'ActivateController@activate');

		// Delete
		Route::post('delete', 'DeleteController@delete');

		// Restore
		Route::post('restore', 'RestoreController@restore');

	});

});

// Comments
Route::namespace('Comments')->prefix('comments')->group(function() {

	// Comments
	Route::get('/', 'CommentsController@comments');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Publish
		Route::post('publish', 'PublishController@publish');

		// Delete
		Route::post('delete', 'DeleteController@delete');

		// Restore
		Route::post('restore', 'RestoreController@restore');

		// Update
		Route::post('update', 'UpdateController@update');

		// Spam
		Route::post('spam', 'SpamController@spam');

	});

});

// Fetch
Route::namespace('Fetch')->prefix('ajax/fetch')->group(function() {

	// Notifications
	Route::post('notifications', 'NotificationsController@notifications');

});

// Reports
Route::namespace('Reports')->prefix('reports')->group(function() {

	// Deals
	Route::namespace('Deals')->prefix('deals')->group(function() {

		// Reports
		Route::get('/', 'DealsController@deals');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Delete 
			Route::post('delete', 'DeleteController@delete');

			// Restore
			Route::post('read', 'ReadController@read');

		});

	});

	// Comments
	Route::namespace('Comments')->prefix('comments')->group(function() {

		// Reports
		Route::get('/', 'CommentsController@comments');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Delete 
			Route::post('delete', 'DeleteController@delete');

			// Restore
			Route::post('read', 'ReadController@read');

		});

	});

});