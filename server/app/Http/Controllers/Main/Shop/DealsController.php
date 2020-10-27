<?php

namespace App\Http\Controllers\Main\Shop;

use App\Http\Controllers\Controller;
use App\Model\Classified;
use App\Model\Store;
use Illuminate\Http\Request;

class DealsController extends Controller
{

	/**
	 * Get deals by shop
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
    public function deals($name)
    {
    	// Check shop
    	$shop = Store::whereStore($name)
                     ->where('isActive', true)
                     ->where('isPending', false)
                     ->where('isExpired', false)
                     ->firstOrFail();

        // Get deals
        $deals = Classified::with(array('user' => function($query) {
                                $query->select('id', 'avatar');
                            }))
                           ->whereUserId($shop->user_id)
        				   ->where('isActive', true)
        				   ->where('isPending', false)
        				   ->latest()
        				   ->paginate(50);

        return response($deals);
    }
}
