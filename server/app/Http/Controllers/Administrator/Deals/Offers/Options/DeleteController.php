<?php

namespace App\Http\Controllers\Administrator\Deals\Offers\Options;

use App\Http\Controllers\Controller;
use App\Model\Offer;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-deals');
    }


    /**
     * Delete selected offers
     * @param  Request $request [description]
     * @param  Offer   $offers  [description]
     * @return [type]           [description]
     */
    public function delete(Request $request, Offer $offers)
    {
    	// Loop trough offers
        foreach ($request->offers as $offer) {
            $offers->whereUniqueId($offer['unique_id'])->delete();
        }

        // Success
        return response()->json([], 200);
    }
}
