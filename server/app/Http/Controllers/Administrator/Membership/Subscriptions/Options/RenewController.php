<?php

namespace App\Http\Controllers\Administrator\Membership\Subscriptions\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Subscription;

class RenewController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-subscriptions');
    }


    /**
     * Renew subscription
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function renew(Request $request)
    {
    	// Check subscription
    	$subscription = Subscription::whereUniqueId($request->id)->firstOrFail();

    	// Make valiation
    	$request->validate([
    		'days' => 'required|integer'
    	]);

    	// Update subscription
		$subscription->isExpired  = false;
		$subscription->expires_at = now()->addDays($request->days);
    	$subscription->save();

    	// Success
    	return response($subscription, 200);
    }
}
