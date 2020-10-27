<?php

namespace App\Http\Controllers\Administrator\Membership\Subscriptions\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Subscription;

class RestoreController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-subscriptions');
    }


    /**
     * Restore selected subscriptions
     * @param  Request      $request       [description]
     * @param  Subscription $subscriptions [description]
     * @return [type]                      [description]
     */
    public function restore(Request $request, Subscription $subscriptions)
    {
    	// Loop trough subscriptions
        foreach ($request->subscriptions as $subscription) {
            $subscriptions->whereUniqueId($subscription['unique_id'])->restore();
        }

        // Success
        return response()->json([], 200);
    }
}
