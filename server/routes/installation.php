<?php

// Welcome
Route::post('welcome', 'WelcomeController@welcome');

// Verify
Route::post('verify', 'VerifyController@verify');

// Requirements
Route::post('requirements', 'RequirementsController@requirements');

// Database
Route::post('database', 'DatabaseController@database');

// Webiste
Route::post('website', 'WebsiteController@website');

// Account
Route::post('account', 'AccountController@account');

// Finish
Route::post('finish', 'FinishController@finish');