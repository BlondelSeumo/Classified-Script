<?php

namespace App\Http\Controllers\Manager\Shops\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Store;

class RestoreController extends Controller
{
    
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
    }



    /**
     * Restore deleted shops
     * @param  Request $request [description]
     * @param  Store   $shops   [description]
     * @return [type]           [description]
     */
    public function restore(Request $request, Store $shops)
    {
    	// Loop trough shops
        foreach ($request->shops as $shop) {
            $shops->whereUniqueId($shop['unique_id'])->whereUserId(auth()->id())->restore();
        }

        // Success
        return response()->json([], 200);
    }
}
