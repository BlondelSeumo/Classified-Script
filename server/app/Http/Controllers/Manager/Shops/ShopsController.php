<?php

namespace App\Http\Controllers\Manager\Shops;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Store;

class ShopsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
    }


    /**
     * Get all shops by current manager
     * @return [type] [description]
     */
    public function shops()
    {
    	// Get all shops
    	$shops = Store::with('country')
    				  ->whereUserId(auth()->id())
    				  ->withTrashed()
    				  ->latest()
    				  ->paginate(100);

    	return response()->json($shops, 200);
    }
}
