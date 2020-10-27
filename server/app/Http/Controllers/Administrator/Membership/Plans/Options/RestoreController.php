<?php

namespace App\Http\Controllers\Administrator\Membership\Plans\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Plan;

class RestoreController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-delete-packages');
    }


    /**
     * Restore selected plans
     * @param  Request $request [description]
     * @param  Plan    $plans   [description]
     * @return [type]           [description]
     */
    public function restore(Request $request, Plan $plans)
    {
    	// Loop trough plans
        foreach ($request->plans as $plan) {
            $plans->whereUniqueId($plan['unique_id'])->restore();
        }

        // Success
        return response()->json([], 200);
    }
}
