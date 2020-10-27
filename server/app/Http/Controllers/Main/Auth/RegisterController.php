<?php

namespace App\Http\Controllers\Main\Auth;

use App\Model\User;
use App\Model\SettingsAuth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\BlacklistEmail;
use App\Model\EmailActivation;
use App\Rules\BlacklistUsername;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Notifications\Auth\Activation;
use App\Jobs\Main\Auth\JobAccountCreated;
use App\Jobs\Main\Auth\JobEmailActivation;
use App\Jobs\Administrator\Pending\JobPendingUser;

class RegisterController extends Controller
{

    /**
     * Create new account
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function register(Request $request)
    {

        // Get authentication settings
        $settings = SettingsAuth::find(1);

        // Check if register enabled
        if (!$settings->isRegister) {
            return response('register_disabled', 422);
        }

    	// Validate request
    	$this->validateRegister($request);

        // Create user
        $user = User::create([
            'email'     => $request->email,
            'username'  => $request->username,
            'password'  => bcrypt($request->password),
            'role_id'   => $settings->default_role_id ?? 1,
            'plan_id'   => $settings->default_plan_id,
            'isActive'  => $settings->needActivation ? false : true,
            'isPending' => $settings->needActivation ? true : false,
        ]);

        // Send notification to user 
        JobAccountCreated::dispatch($user);

        // Check if register requires activation
        if ($settings->needActivation) {

            // Check if activation via email
            if ($settings->activationType == 'email') {

                // Send activation email
                $this->activation($user, $settings); 

                // Activation required response
                return response('activation_required_email');
            }

            // Check if activation manually
            if ($settings->activationType == 'manually') {

                // Send notifications to admin
                JobPendingUser::dispatch($user);
                
                // Activation required response
                return response('activation_required_manually');
            }

            
        }

        // No activation required, Get token
        $token = JWTAuth::fromUser($user);
        
        // Return token
        return response($token);
    }


    /**
     * Validate Register form
     * @return [type] [description]
     */
    public function validateRegister(Request $request)
    {
    	$request->validate([
			'email'    => ['required', 'email', 'max:60', 'unique:users', new BlacklistEmail],
			'username' => ['required', 'max:60', 'unique:users', new BlacklistUsername],
			'password' => 'required|max:60'
    	]);
    }



    /**
     * Send user notification about activation
     * @param  User         $user     [description]
     * @param  SettingsAuth $settings [description]
     * @return [type]                 [description]
     */
    private function activation(User $user, SettingsAuth $settings)
    {
        // Generate activation code
        $token      = hash_hmac('sha256', Str::random(40), config('app.key'));

        // Save activation code
        $activation = EmailActivation::create([
            'email'   => $user->email,
            'user_id' => $user->id,
            'token'   => $token
        ]);

        // Send notification to user by email
        JobEmailActivation::dispatch($user, $token);
    }
}
