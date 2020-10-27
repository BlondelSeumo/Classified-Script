<?php

namespace App\Http\Controllers\Main\Shop\Options;

use App\Model\Store;
use App\Model\Follower;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\Manager\Followers\NewFollower;

class FollowController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Follow store
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function follow(Request $request)
    {
    	// Check if shop exists
		$shop     = Store::where('store', $request->shop)
					     ->where('isActive', true)
					     ->where('isPending', false)
					     ->where('isExpired', false)
					     ->firstOrFail();
		
		// Check if user already follo this shop
		$follower = Follower::firstOrCreate([
										'user_id'  => auth()->id(), 
										'store_id' => $shop->id
									]);
									
		// Send notification
		$shop->owner->notify(new NewFollower(auth()->user()->username));

    	// Success
    	return response([]);
    }
}
