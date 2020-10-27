<?php

namespace App\Http\Controllers\Administrator\Membership\Subscriptions\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Subscription;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-subscriptions');
    }


    /**
     * Delete selected subscriptions
     * @param  Request      $request       [description]
     * @param  Subscription $subscriptions [description]
     * @return [type]                      [description]
     */
    public function delete(Request $request, Subscription $subscriptions)
    {
    	// Loop trough subscriptions
        foreach ($request->subscriptions as $subscription) {
            $subscriptions->whereUniqueId($subscription['unique_id'])->delete();
        }

        // Success
        return response()->json([], 200);
    }
}
