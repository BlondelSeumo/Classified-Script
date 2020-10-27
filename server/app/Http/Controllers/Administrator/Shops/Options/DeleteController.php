<?php

namespace App\Http\Controllers\Administrator\Shops\Options;

use App\Model\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\Manager\Shops\ShopDeleted;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-shops');
    }


    /**
     * Delete selected shops
     * @param  Request $request [description]
     * @param  Store   $shops   [description]
     * @return [type]           [description]
     */
    public function delete(Request $request, Store $shops)
    {
    	// Loop trough shops
        foreach ($request->shops as $shop) {
            // Check shop
            $exists = $shops->isNotAdminShop()->whereUniqueId($shop['unique_id'])->first();

            if ($exists) {
                
                // Delete shop
                $exists->delete();

                // Send notification
                $exists->owner->notify(new ShopDeleted($exists));

            }
        }

        // Success
        return response()->json([], 200);
    }
}
