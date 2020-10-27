<?php

namespace App\Http\Controllers\Administrator\Deals\Offers\Options;

use App\Http\Controllers\Controller;
use App\Model\Offer;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-deals');
    }


    /**
     * Restore deleted offers
     * @param  Request $request [description]
     * @param  Offer   $offers  [description]
     * @return [type]           [description]
     */
    public function restore(Request $request, Offer $offers)
    {
    	// Loop trough offers
    	foreach ($request->offers as $deal) {
    		$offers->whereUniqueId($deal['unique_id'])->restore();
    	}

    	// Success
    	return response()->json([], 200);
    }
}
