<?php

namespace App\Http\Controllers\Administrator\Shops;

use App\Http\Controllers\Controller;
use App\Model\Store;
use Illuminate\Http\Request;

class ShopsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-shops');
    }


    /**
     * Get all shops
     * @return [type] [description]
     */
    public function shops()
    {
    	// Get all shops
    	$shops = Store::with('country', 'owner')->withTrashed()->latest()->paginate(100);

    	return response()->json($shops, 200);
    }
}
