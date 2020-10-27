<?php

// Auth
Route::namespace('Main\Auth')->prefix('auth')->group(function () {

    // Register
    Route::post('register', 'RegisterController@register');

    // Login
    Route::get('login', 'LoginController@settings');
    Route::post('login', 'LoginController@login');

    // Verify 2FA
    Route::post('verify', 'LoginController@verify');

    // Refresh Token
    Route::get('refresh', 'TokenController@refresh');

    // Activation
    Route::post('activation', 'ActivationController@activation');

    // Get User
    Route::middleware('auth:api')->get('user', 'TokenController@user');

    // Logout
    Route::middleware('auth:api')->post('logout', 'LogoutController@logout');

    // Password
    Route::namespace('Password')->prefix('password')->group(function() {

        // Reset password
        Route::get('reset', 'ResetController@settings');
        Route::post('reset', 'ResetController@reset');

        // Update password
        Route::get('update', 'UpdateController@settings');
        Route::post('update', 'UpdateController@update');

    });

    // Social media
    Route::namespace('Social')->group(function() {

        // Facebook
        Route::post('facebook', 'FacebookController@login');

        // Twitter
        Route::get('twitter', 'TwitterController@redirect');
        Route::post('twitter', 'TwitterController@login');

        // Google
        Route::post('google', 'GoogleController@login');

        // Instagram
        Route::post('instagram', 'InstagramController@login');

        // Linkedin
        Route::post('linkedin', 'LinkedinController@login');

        // Vk
        Route::post('vk', 'VkController@login');

    });

});

// Fetch image
Route::get('fetch/image/{image}', function($image) {

    // Explode url
    $parts = explode('-', $image);

    // Add headers
    $headers = [
        'Access-Control-Allow-Origin' => '*'
    ];

    // Get file
    $file = storage_path("app/uploads/classifieds/$parts[0]/$parts[1]");

    // Check if file exists
    if (file_exists($file)) {
        
            // Return file
            return response()->file($file, $headers);

    }else{

        // Not found
        return response([], 404);

    }

});