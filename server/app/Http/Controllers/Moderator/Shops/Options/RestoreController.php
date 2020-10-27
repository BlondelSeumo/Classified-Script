<?php

namespace App\Http\Controllers\Moderator\Shops\Options;

use App\Http\Controllers\Controller;
use App\Model\Store;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-delete-shops');
    }


    /**
     * Delete selected shops
     * @param  Request $request [description]
     * @param  Store   $shops   [description]
     * @return [type]           [description]
     */
    public function restore(Request $request, Store $shops)
    {
    	// Loop trough shops
        foreach ($request->shops as $shop) {
            $shops->whereUniqueId($shop['unique_id'])->restore();
        }

        // Success
        return response()->json([], 200);
    }
}
