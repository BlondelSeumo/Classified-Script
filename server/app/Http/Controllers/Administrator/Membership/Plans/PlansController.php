<?php

namespace App\Http\Controllers\Administrator\Membership\Plans;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Plan;

class PlansController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-packages');
    }



    /**
     * get plans
     * @return [type] [description]
     */
    public function plans()
    {
    	$plans = Plan::latest()->withTrashed()->paginate(100);

    	return response()->json($plans, 200);
    }
}
