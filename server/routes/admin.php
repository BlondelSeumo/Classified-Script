<?php

// Dashboard
Route::namespace('Home')->group(function() {

	// Home page
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

	// Users
	Route::get('users', 'UsersController@users');

	// Comments
	Route::get('comments', 'CommentsController@comments');

});

// Categories
Route::namespace('Categories')->prefix('categories')->group(function() {

	// Categories
	Route::get('/', 'CategoriesController@categories');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Create
		Route::post('create', 'CreateController@create');

		// Edit
		Route::post('edit', 'EditController@edit');
		Route::post('update', 'EditController@update');

	});

});

// Fields
Route::namespace('Fields')->prefix('fields')->group(function() {

	// Fields
	Route::get('/', 'FieldsController@fields');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Create
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

// Deals
Route::namespace('Deals')->prefix('deals')->group(function() {

	// Deals
	Route::get('/', 'DealsController@deals');

	// Packages
	Route::namespace('Packages')->prefix('packages')->group(function() {

		// Packages
		Route::get('/', 'PackagesController@packages');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Create
			Route::post('create', 'CreateController@create');

		});

	});

	// Payments
	Route::namespace('Payments')->prefix('payments')->group(function() {

		// Payments
		Route::get('/', 'PaymentsController@payments');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Accept
			Route::post('accept', 'AcceptController@accept');

			// Decline
			Route::post('decline', 'DeclineController@decline');

		});

	});

	// Offers
	Route::namespace('Offers')->prefix('offers')->group(function() {

		// Offers
		Route::get('/', 'OffersController@offers');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Delete
			Route::post('delete', 'DeleteController@delete');

			// Restore
			Route::post('restore', 'RestoreController@restore');

		});

	});

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {
		
		// Fetch data
		Route::post('create/fetch', 'CreateController@fetch');

		// create
		Route::post('create', 'CreateController@create');

		// Edit
		Route::get('edit/{id}', 'EditController@edit');
		Route::post('edit', 'EditController@update');

		// Publish
		Route::post('publish', 'PublishController@publish');

		// Delete
		Route::post('delete', 'DeleteController@delete');

		// Restore
		Route::post('restore', 'RestoreController@restore');

		// Archive
		Route::post('archive', 'ArchiveController@archive');

		// Upgrade
		Route::post('upgrade', 'UpgradeController@upgrade');
		
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

		// Activate
		Route::post('activate', 'ActivateController@activate');

		// Delete
		Route::post('delete', 'DeleteController@delete');

		// Restore
		Route::post('restore', 'RestoreController@restore');

	});

});

// Orders
Route::namespace('Orders')->prefix('orders')->group(function() {

	// Orders
	Route::get('/', 'OrdersController@orders');

});

// Refunds
Route::namespace('Refunds')->prefix('refunds')->group(function() {

	// Refunds
	Route::get('/', 'RefundsController@refunds');

});

// Users
Route::namespace('Users')->prefix('users')->group(function() {

	// Users
	Route::get('/', 'UsersController@users');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Create
		Route::get('create', 'CreateController@fetch');
		Route::post('create', 'CreateController@create');

		// Edit
		Route::post('edit', 'EditController@edit');
		Route::post('update', 'EditController@update');

		// Activate
		Route::post('activate', 'ActivateController@activate');

		// Block
		Route::post('block', 'BlockController@block');

		// Unblock
		Route::post('unblock', 'UnblockController@unblock');

		// Delete 
		Route::post('delete', 'DeleteController@delete');

		// Restore
		Route::post('restore', 'RestoreController@restore');

		// Statistics
		Route::post('statistics', 'StatisticsController@statistics');

	});

});

// Roles
Route::namespace('Roles')->prefix('roles')->group(function() {

	// Roles
	Route::get('/', 'RolesController@roles');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Create
		Route::namespace('Create')->prefix('create')->group(function() {

			// Owner
			Route::post('owner', 'OwnerController@create');

			// Administrator
			Route::post('administrator', 'AdministratorController@create');

			// Moderator
			Route::post('moderator', 'ModeratorController@create');

			// Member
			Route::post('member', 'MemberController@create');

		});

		// Delete
		Route::post('delete', 'DeleteController@delete');

		// Edit
		Route::get('edit/{id}', 'EditController@edit');
		Route::post('edit', 'EditController@update');

	});

});

// Comments
Route::namespace('Comments')->prefix('comments')->group(function() {

	// Comments
	Route::get('/', 'CommentsController@comments');

	// Replies
	Route::get('replies', 'RepliesController@replies');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Publish
		Route::post('publish', 'PublishController@publish');

		// Update
		Route::post('update', 'UpdateController@update');

		// Delete
		Route::post('delete', 'DeleteController@delete');

		// Restore
		Route::post('restore', 'RestoreController@restore');

		// Spam
		Route::post('spam', 'SpamController@spam');

	});

});

// Conversations
Route::namespace('Conversations')->prefix('conversations')->group(function() {

	// Users
	Route::namespace('Users')->prefix('users')->group(function() {

		// Get messages
		Route::get('/', 'MessagesController@messages');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Delete
			Route::post('delete', 'DeleteController@delete');

		});

	});

	// Admin
	Route::namespace('Admin')->prefix('admin')->group(function() {

		// Messages
		Route::get('/', 'MessagesController@messages');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Read
			Route::post('read', 'ReadController@read');

			// Reply
			Route::post('reply', 'ReplyController@reply');

			// Delete
			Route::post('delete', 'DeleteController@delete');

		});

	});

	// Chat
	Route::namespace('Chat')->prefix('chat')->group(function() {

		// Rooms
		Route::get('/', 'RoomsController@rooms');

		// Messages
		Route::get('room/{id}', 'MessagesController@messages');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Delete
			Route::post('delete', 'DeleteController@delete');

			// Delete messages
			Route::post('messages/delete', 'Messages\DeleteController@delete');

		});

	});

});

// Membership
Route::namespace('Membership')->prefix('membership')->group(function() {

	// Plans
	Route::namespace('Plans')->prefix('plans')->group(function() {

		// Get plans
		Route::get('/', 'PlansController@plans');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Create
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

	// Subscriptions
	Route::namespace('Subscriptions')->prefix('subscriptions')->group(function() {

		// Get subscriptions
		Route::get('/', 'SubscriptionsController@subscriptions');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Renew
			Route::post('renew', 'RenewController@renew');

			// Delete
			Route::post('delete', 'DeleteController@delete');

			// Restore
			Route::post('restore', 'RestoreController@restore');

		});

	});

});

// Blog
Route::namespace('Blog')->prefix('blog')->group(function() {

	// Get articles
	Route::get('/', 'BlogController@blog');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Create
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

// Currencies
Route::namespace('Currencies')->prefix('currencies')->group(function() {

	// Currencies
	Route::get('/', 'CurrenciesController@currencies');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Fetch settings
		Route::get('fetch', 'FetchController@fetch');

		// Create
		Route::post('create', 'CreateController@create');

		// Edit
		Route::post('edit', 'EditController@edit');

		// Delete
		Route::post('delete', 'DeleteController@delete');

		// Restore
		Route::post('restore', 'RestoreController@restore');

	});

});

// Pages
Route::namespace('Pages')->prefix('pages')->group(function() {

	// Pages
	Route::get('/', 'PagesController@pages');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Create
		Route::get('create', 'CreateController@fetch');
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

// Geolocation
Route::namespace('Geolocation')->prefix('geolocation')->group(function() {

	// Countries
	Route::namespace('Countries')->prefix('countries')->group(function() {

		// Get
		Route::get('/', 'CountriesController@countries');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Create
			Route::get('create', 'CreateController@fetch');
			Route::post('create', 'CreateController@create');

			// Edit
			Route::post('edit', 'EditController@edit');

		});

	});

	// States
	Route::namespace('States')->prefix('states')->group(function() {

		// Get
		Route::get('/', 'StatesController@states');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Create
			Route::get('create', 'CreateController@fetch');
			Route::post('create', 'CreateController@create');

			// Edit
			Route::post('edit', 'EditController@edit');

		});

	});

	// Cities
	Route::namespace('Cities')->prefix('cities')->group(function() {

		// Get
		Route::get('/', 'CitiesController@cities');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Create
			Route::get('create', 'CreateController@fetch');
			Route::post('create', 'CreateController@create');

			// Edit
			Route::post('edit', 'EditController@edit');

		});

	});

});

// Advertisements
Route::namespace('Advertisements')->prefix('advertisements')->group(function() {

	// Adsense
	Route::namespace('Adsense')->group(function() {

		// Get ads
		Route::get('/', 'AdvertisementsController@advertisements');
		Route::post('/', 'AdvertisementsController@update');

	});

	// Companies
	Route::namespace('Companies')->prefix('companies')->group(function() {

		// 

	});

});

// Servies
Route::namespace('Servies')->prefix('services')->group(function() {

	// SMS
	Route::namespace('Sms')->prefix('sms')->group(function() {

		// Sms gateways
		Route::post('/', 'GatewaysController@gateways');

		// Gateways
		Route::namespace('Gateways')->group(function() {

			// Nexmo
			Route::get('nexmo', 'NexmoController@settings');
			Route::post('nexmo', 'NexmoController@update');

		});

	});

	// Clouds
	Route::namespace('Clouds')->prefix('clouds')->group(function() {

		// Clouds gateways
		Route::post('/', 'GatewaysController@gateways');

	});

});

// Themes
Route::namespace('Themes')->prefix('themes')->group(function() {

	// Themes
	Route::post('/', 'ThemesController@themes');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Activate 
		Route::post('activate', 'ActivateController@activate');

		// Request
		Route::post('request', 'RequestController@request');

	});

});

// Settings
Route::namespace('Settings')->prefix('settings')->group(function() {

	// General
	Route::get('general', 'GeneralController@settings');
	Route::post('general', 'GeneralController@update');

	// Home
	Route::get('home', 'HomeController@settings');
	Route::post('home', 'HomeController@update');

	// Authentication
	Route::get('auth', 'AuthController@settings');
	Route::post('auth', 'AuthController@update');

	// SMTP
	Route::get('smtp', 'SmtpController@settings');
	Route::post('smtp', 'SmtpController@update');

	// Security
	Route::get('security', 'SecurityController@settings');
	Route::post('security', 'SecurityController@update');

	// Geolocation
	Route::get('geo', 'GeoController@settings');
	Route::post('geo', 'GeoController@update');

	// Seo
	Route::get('seo', 'SeoController@settings');
	Route::post('seo', 'SeoController@update');

	// Uploading
	Route::get('upload', 'UploadController@settings');
	Route::post('upload', 'UploadController@update');

	// Posting
	Route::get('posting', 'PostingController@settings');
	Route::post('posting', 'PostingController@update');

	// Payment gateways
	Route::get('payments', 'PaymentsController@settings');
	Route::post('payments', 'PaymentsController@update');

	// Social media
	Route::get('social', 'SocialController@settings');
	Route::post('social', 'SocialController@update');

	// Watermark
	Route::get('watermark', 'WatermarkController@settings');
	Route::post('watermark', 'WatermarkController@update');

	// Footer
	Route::get('footer', 'FooterController@settings');
	Route::post('footer', 'FooterController@update');

});

// Fetch
Route::namespace('Fetch')->prefix('ajax/fetch')->group(function() {

	// Categories
	Route::post('categories', 'CategoriesController@categories');
	Route::post('categories/all', 'CategoriesController@all');

	// Child categories
	Route::post('categories/childs', 'CategoriesController@childs');

	// Currencies
	Route::post('currencies', 'CurrenciesController@currencies');

	// Notifications
	Route::post('notifications', 'NotificationsController@notifications');

});

// Maintenance
Route::namespace('Maintenance')->prefix('maintenance')->group(function() {

	// Config
	Route::get('/', 'MaintenanceController@settings');
	Route::post('/', 'MaintenanceController@update');

	// Generate backup link
	Route::post('generate', 'MaintenanceController@generate');

});

// Support
Route::namespace('Support')->prefix('support')->group(function() {

	// Contact
	Route::post('contact', 'ContactController@contact');

	// Settings
	Route::get('settings', 'SettingsController@settings');
	Route::post('settings', 'SettingsController@update');

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