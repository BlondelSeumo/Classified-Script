<?php

namespace App\Http\Controllers\Moderator\Shops;

use App\Http\Controllers\Controller;
use App\Model\Store;
use Illuminate\Http\Request;

class ShopsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-access-shops');
    }


    /**
     * Get latest shops
     * @return [type] [description]
     */
    public function shops()
    {
    	// Get shops
    	$shops = Store::with('country')->withoutAdminShops()->withTrashed()->latest()->paginate(10);

    	return response($shops);
    }
}
