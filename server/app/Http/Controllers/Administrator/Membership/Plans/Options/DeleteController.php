<?php

namespace App\Http\Controllers\Administrator\Membership\Plans\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Plan;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-delete-packages');
    }


    /**
     * Delete selected plans
     * @param  Request $request [description]
     * @param  Plan    $plans   [description]
     * @return [type]           [description]
     */
    public function delete(Request $request, Plan $plans)
    {
    	// Loop trough plans
        foreach ($request->plans as $plan) {
            $plans->whereUniqueId($plan['unique_id'])->delete();
        }

        // Success
        return response()->json([], 200);
    }
}
