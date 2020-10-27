<?php

namespace App\Http\Controllers\Main\Subscription\Create;

use Stripe\Charge;
use Stripe\Stripe;
use App\Model\Plan;
use App\Model\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Administrator\Subscriptions\JobNewSubscription;

class AccountController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * Create new account subscription
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate request
    	$request->validate([
    		'token' => 'required',
    		'plan'  => 'required|exists:plans,slug'
    	]);

    	// Get token
    	$token = $request->token['id'];

    	// Get plan
    	$plan = Plan::whereSlug($request->plan)->first();

    	// Check if plan not found
    	if (!$plan) {
    		
    		// Not found
    		return response()->json([
    			'status' => 'not_found'
    		], 422);

    	}

    	// Set Strip secret key
    	Stripe::setApiKey("sk_test_LRFbNEFtDhXVrXgFJlByHw7U");

    	// Charge user
		$charge = Charge::create([
			'amount'      => $plan->price * 100,
			'currency'    => $plan->currency,
			'description' => $plan->description,
			'source'      => $token,
		]);

		// Check if payment succeeded
		if ( $charge['paid'] && ($charge['status'] == 'succeeded') ) {

			// Create new subscription
			$subscription = Subscription::create([
				'unique_id'      => uniqueId(),
				'user_id'        => auth()->id(),
				'transaction_id' => $charge['id'],
				'plan_id'        => $plan->id,
				'gateway'        => 'stripe',
				'expires_at'     => $this->expiresAt($plan->frequency)
			]);

			// Send notification to owners
			JobNewSubscription::dispatch($subscription);

			// Success
			return response()->json([
				'status' => 'paid'
			], 200);

		}else{

			// Not paid
			return response()->json([
				'status' => 'failed'
			], 422);

		}
    }


    /**
     * Check plan expires at
     * @param  [type] $frequency [description]
     * @return [type]            [description]
     */
    private function expiresAt($frequency)
    {
    	switch ($frequency) {
    		// Yearly
    		case 'yearly':
    			return now()->addYear();
    			break;

    		// Monthly
    		case 'monthly':
    			return now()->addMonth();
    			break;

    		// Weekly
			case 'weekly':
    			return now()->addWeek();
    			break;

    		// Daily
			case 'daily':
    			return now()->addDays(2);
    			break;

    		// default year
    		default:
    			return now()->addYear();
    			break;
    	}
    }
}
