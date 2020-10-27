<?php

namespace App\Http\Controllers\Main\Auth;

use App\Http\Controllers\Controller;
use App\Model\EmailActivation;
use App\Model\SettingsAuth;
use App\Notifications\Auth\WelcomeMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    function __construct()
    {
    	$this->middleware('guest');
    }



    /**
     * Activate user
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function activation(Request $request)
    {
    	// Check token
    	$token = EmailActivation::whereToken($request->token)->whereUsed(false)->firstOrFail();

    	// Check if token expired
    	if ($this->expired($token)) {

    		// Make token useless
    		$token->used = true;
    		$token->save();

    		// Return response
    		return response('expired');
    	}

    	// Activate user
    	$token->user()->update([
			'isActive'  => true,
			'isPending' => false
    	]);

    	// Send welcome message
    	$token->user->notify(new WelcomeMessage);

    	// Make token useless
    	$token->used = true;
    	$token->save();

    	// Activated response
    	return response('activated');
    }



    /**
     * Check if token expired
     * @param  [type] $token [description]
     * @return [type]        [description]
     */
    private function expired($token)
    {
    	// Get authentication settings
		$settings = SettingsAuth::find(1);

		$created  = new Carbon($token->created_at);
		
		$cDate    = Carbon::parse($created);
		
		$expired  = $cDate->diffInMinutes();

		// Check if token expired
		return $expired > $settings->activation_expires_after;
    }
}
