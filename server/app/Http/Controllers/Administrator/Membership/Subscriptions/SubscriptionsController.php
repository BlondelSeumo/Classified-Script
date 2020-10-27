<?php

namespace App\Http\Controllers\Administrator\Membership\Subscriptions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Subscription;

class SubscriptionsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-subscriptions');
    }


    /**
     * Get latest subscriptions
     * @return [type] [description]
     */
    public function subscriptions()
    {
    	// Get subscriptions
    	$subscriptions = Subscription::with('user', 'plan')->latest('subscribed_at')->withTrashed()->paginate(50);

    	return response($subscriptions, 200);
    }
}
