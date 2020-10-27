<?php

namespace App\Http\Controllers\Administrator\Deals\Options;

use App\Model\Classified;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Main\Deals\JobDealDeleted;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-deals');
    }


    /**
     * Delete selected deals
     * @param  Request    $request [description]
     * @param  Classified $deals   [description]
     * @return [type]              [description]
     */
    public function delete(Request $request, Classified $deals)
    {
    	// Loop trough deals
        foreach ($request->deals as $deal) {
            $classified = $deals->isNotAdminDeals()->whereUniqueId($deal['unique_id'])->first();

            if ($classified) {
                $classified->delete();

                // Send notification
                JobDealDeleted::dispatch($classified);
            }
        }

        // Success
        return response()->json([], 200);
    }
}
