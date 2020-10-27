<?php

// Home
Route::namespace('Home')->prefix('home')->group(function() {

	// Get home
	Route::get('/', 'HomeController@home');

});

// Browse
Route::namespace('Browse')->prefix('browse')->group(function() {

	// Deals
	Route::get('deals', 'DealsController@deals');

	// Products
	Route::get('products', 'ProductsController@products');

	// Shops
	Route::get('shops', 'ShopsController@shops');

});

// Category
Route::namespace('Category')->prefix('category')->group(function() {

	// Get category
	Route::get('{slug}', 'CategoryController@category');

});

// Account
Route::middleware('auth:api')->namespace('Account')->prefix('account')->group(function() {

	// Settings
	Route::get('settings', 'SettingsController@fetch');
	Route::post('settings', 'SettingsController@update');

	// Deals
	Route::namespace('Deals')->prefix('deals')->group(function() {

		// Deals
		Route::get('/', 'DealsController@deals');

		// Promote
		Route::namespace('Promote')->prefix('promote')->group(function() {

			// Promote
			Route::post('/', 'PromoteController@promote');
			Route::post('checkout', 'PromoteController@checkout');

		});

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Delete
			Route::post('delete', 'DeleteController@delete');

			// Restore
			Route::post('restore', 'RestoreController@restore');

			// Archive
			Route::post('archive', 'ArchiveController@archive');

			// Edit
			Route::post('edit', 'EditController@edit');
			Route::post('update', 'EditController@update');

			// Delete image
			Route::post('image', 'ImageController@delete');

			// Statistics
			Route::post('statistics', 'StatisticsController@statistics');

		});

	});

	// Offers
	Route::namespace('Offers')->prefix('offers')->group(function() {

		// Offers
		Route::get('/', 'OffersController@offers');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Accept
			Route::post('accept', 'AcceptController@accept');

			// Refuse
			Route::post('refuse', 'RefuseController@refuse');

			// Send
			Route::post('send', 'SendController@send');

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

	// Subscription
	Route::namespace('Subscription')->prefix('subscription')->group(function() {

		// Subscription
		Route::get('/', 'SubscriptionController@subscription');

	});

	// Search
	Route::namespace('Search')->prefix('search')->group(function() {

		// Saved search
		Route::get('/', 'SearchController@saved');

		// Options
		Route::namespace('Options')->prefix('options')->group(function() {

			// Create
			Route::post('create', 'CreateController@create');

			// Delete
			Route::post('delete', 'DeleteController@delete');

		});

	});

	// Following
	Route::get('following', 'FollowingController@following');
	Route::post('following/unfollow', 'FollowingController@unfollow');
	Route::post('following/enable', 'FollowingController@enable');
	Route::post('following/disable', 'FollowingController@disable');

	//

	// Wishlist
	Route::namespace('Wishlist')->prefix('wishlist')->group(function() {

		// Add
		Route::post('create', 'CreateController@create');

	});

	// Two Factor Authentication
	Route::prefix('2fa')->group(function() {

		// 2FA
		Route::post('/','TwoFactorAuthController@form');

		// Generate Secret
		Route::post('generate','TwoFactorAuthController@generate');

		// Enable
		Route::post('enable','TwoFactorAuthController@enable');

		// Disable
		Route::post('disable','TwoFactorAuthController@disable');

	});

});

// Chat
Route::namespace('Chat')->prefix('chat')->group(function() {

	// Conversations
	Route::post('/', 'ConversationsController@conversations');

	// Room
	Route::post('conversation', 'MessagesController@messages');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Send 
		Route::post('send', 'SendController@send');

		// Start
		Route::post('start', 'StartController@start');

		// Block
		Route::post('block', 'BlockController@block');

		// Delete
		Route::post('delete', 'DeleteController@delete');

	});

});

// Conversations
Route::middleware('auth:api')->namespace('Conversations')->prefix('conversations')->group(function() {

	// Chat
	Route::namespace('Chat')->prefix('chat')->group(function() {

		// Start chat
		Route::post('start/{username}', 'StartController@start');

		// Send message
		Route::post('send', 'SendController@send');

		// Rooms
		Route::post('rooms', 'RoomsController@rooms');

		// Messages
		Route::post('messages/latest', 'MessagesController@latest');
		Route::post('messages/all', 'MessagesController@all');

	});

	// Inbox
	Route::namespace('Inbox')->prefix('inbox')->group(function() {


	});

});

// Subscription
Route::middleware('auth:api')->namespace('Subscription')->prefix('subscription')->group(function() {

	// Create
	Route::namespace('Create')->prefix('create')->group(function() {

		// Account
		Route::post('account', 'AccountController@create');

	});

});

// Create
Route::middleware('auth:api')->namespace('Create')->prefix('create')->group(function() {

	// create
	Route::post('fetch', 'CreateController@fetch');
	Route::post('/', 'CreateController@create');

});

// Redirect
Route::namespace('Redirect')->prefix('a')->group(function() {

	// Redirect
	Route::post('{id}', 'RedirectController@redirect');

});

// Listing
Route::namespace('Listing')->prefix('listing')->group(function() {

	// Contact
	Route::post('contact', 'ContactController@contact');

	// Comments
	Route::get('{id}/comments', 'CommentsController@comments');

	// Listing
	Route::post('{slug}', 'ListingController@listing');

});

// Product
Route::namespace('Product')->prefix('product')->group(function() {

	// Product
	Route::post('{slug}', 'ProductController@product');

});

// Comments
Route::middleware('auth:api')->namespace('Comments')->prefix('comments')->group(function() {

	// Create
	Route::namespace('Create')->prefix('create')->group(function() {

		// Deals
		Route::post('deals', 'DealsController@create');

	});

});

// Report
Route::namespace('Report')->prefix('report')->group(function() {

	// Report deal
	Route::post('deal', 'DealsController@report');
	
	// Comments
	Route::post('comment', 'CommentController@report');

});

// Fetch Comments
Route::namespace('Comments\Fetch')->prefix('comments/fetch')->group(function() {

	// Deals
	Route::get('deals', 'DealsController@comments');

});

// Shop
Route::namespace('Shop')->prefix('shop')->group(function() {

	// Shop
	Route::post('{shop}', 'ShopController@shop');

	// Deals
	Route::get('{shop}/deals', 'DealsController@deals');

	// Options
	Route::namespace('Options')->prefix('options')->group(function() {

		// Follow
		Route::post('follow', 'FollowController@follow');

		// Unfollow
		Route::post('unfollow', 'UnfollowController@unfollow');

		// Message
		Route::post('message', 'MessageController@message');

	});

});

// Search
Route::namespace('Search')->prefix('search')->group(function() {

	// Search settings
	Route::post('settings', 'SettingsController@settings');

	// Search
	Route::get('/', 'SearchController@search');

	// Deals
	Route::get('deals', 'DealsController@search');

	// Products
	Route::get('products', 'ProductsController@search');

	// Shops
	Route::get('shops', 'ShopsController@search');

	// Coupons
	Route::get('coupons', 'CouponsController@search');

});

// Pricing
Route::namespace('Pricing')->prefix('pricing')->group(function() {

	// Plans
	Route::post('/', 'PricingController@plans');

	// checkout
	Route::post('checkout/{plan}', 'CheckoutController@checkout')->middleware('auth:api');

});

// Articles
Route::namespace('Blog')->prefix('blog')->group(function() {

	// Blog
	Route::get('/', 'BlogController@blog');

	// Article
	Route::post('{slug}', 'BlogController@article');

});

// Fetch
Route::namespace('Fetch')->prefix('ajax/fetch')->group(function() {

	// Footer pages
	Route::post('pages', 'PagesController@pages');

	// Settings
	Route::post('settings', 'SettingsController@settings');

	// Countries
	Route::post('countries', 'CountriesController@countries');

	// States
	Route::post('states', 'StatesController@states');

	// cities
	Route::post('cities', 'CitiesController@cities');

	// Fields
	Route::post('fields', 'FieldsController@fields');

	// Categories
	Route::post('categories', 'CategoriesController@categories');
	Route::post('categories/childs', 'CategoriesController@childs');

});

// Contact
Route::namespace('Contact')->prefix('contact')->group(function() {

	// Contact
	Route::get('/', 'ContactController@seo');
	Route::post('/', 'ContactController@contact');

	// Feedback
	Route::get('feedback', 'FeedbackController@seo');
	Route::post('feedback', 'FeedbackController@feedback');

});


// Pages
Route::namespace('Pages')->prefix('page')->group(function() {

	// Get page
	Route::post('{slug}', 'PageController@page');

});

// Maintenance
Route::namespace('Maintenance')->prefix('maintenance')->group(function() {

	// Disable
	Route::get('disable', 'DisableController@disable');

});

// Sitemap
Route::namespace('Sitemap')->group(function() {

	// Get sitemap
	Route::post('sitemap', 'SitemapController@generate');

});