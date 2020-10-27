<?php

namespace App\Http\Controllers\Administrator\Shops\Options;

use App\Model\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\Manager\Shops\ShopApproved;

class ActivateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-approve-shops');
    }


    /**
     * Activate selected shops
     * @param  Request $request [description]
     * @param  Store   $shops   [description]
     * @return [type]           [description]
     */
    public function activate(Request $request, Store $shops)
    {
    	// Loop trough shops
        foreach ($request->shops as $shop) {

            // Check if shop exists
            $exists = $shops->whereUniqueId($shop['unique_id'])->first();

            if ($exists) {
                
                // Activate
                $exists->update([
                    'isActive' => true,
                    'isPending' => false,
                    'isExpired' => false
                ]);

                // Restore if deleted
                $exists->restore();

                // Send notification
                $exists->owner->notify(new ShopApproved($exists));

            }
        }

        // Success
        return response()->json([], 200);
    }
}
