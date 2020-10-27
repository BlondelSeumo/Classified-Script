<?php

namespace App\Http\Controllers\Administrator\Advertisements\Adsense;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Advertisement;

class AdvertisementsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-advertisements');
    }



    /**
     * [advertisements description]
     * @return [type] [description]
     */
    public function advertisements()
    {
    	// Get advertisements
    	$advertisements = Advertisement::find(1);

    	return response($advertisements);
    }



    /**
     * Update advertisements
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Update advertismenets
    	$advertisements = Advertisement::find(1);
    	$advertisements->ad_deal_sidebar = $request->ad_deal_sidebar;
    	$advertisements->ad_deal_top_related = $request->ad_deal_top_related;
    	$advertisements->ad_deal_bottom_related = $request->ad_deal_bottom_related;
    	$advertisements->save();

    	// Success
    	return response([]);
    }
}
