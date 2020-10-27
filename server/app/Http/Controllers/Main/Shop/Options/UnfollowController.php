<?php

namespace App\Http\Controllers\Main\Shop\Options;

use App\Http\Controllers\Controller;
use App\Model\Follower;
use App\Model\Store;
use Illuminate\Http\Request;

class UnfollowController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Unfollow store
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function unfollow(Request $request)
    {
    	// Check if shop exists
		$shop     = Store::where('store', $request->shop)
					     ->where('isActive', true)
					     ->where('isPending', false)
					     ->where('isExpired', false)
					     ->firstOrFail();
		
		// Check if user following this shop
		$follower = Follower::whereUserId(auth()->id())->whereStoreId($shop->id)->firstOrFail();

		// Unfollow this shop
		$follower->delete();

    	// Success
    	return response([]);
    }
}
