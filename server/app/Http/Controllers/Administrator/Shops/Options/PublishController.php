<?php

namespace App\Http\Controllers\Administrator\Shops\Options;

use App\Http\Controllers\Controller;
use App\Model\Store;
use Illuminate\Http\Request;

class PublishController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-approve-shops');
    }


    /**
     * Publish pending shops
     * @param  Request $request [description]
     * @param  Store   $shops  [description]
     * @return [type]           [description]
     */
    public function publish(Request $request, Store $shops)
    {
    	// Loop trough shops
    	foreach ($request->shops as $shop) {
    		$shops->whereUniqueId($shop['unique_id'])->update([
    			'isPending' => false,
    			'isActive'  => true
    		]);
    	}

    	// success
    	return response()->json([], 200);
    }
}
