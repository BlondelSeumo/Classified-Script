<?php

namespace App\Http\Controllers\Administrator\Deals\Options;

use App\Model\Classified;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Main\Deals\JobDealApproved;

class PublishController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-approve-deals');
    }


    /**
     * Publish deals
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function publish(Request $request, Classified $classified)
    {
    	// Loop trough deals
    	foreach ($request->deals as $deal) {

            $exists = $classified->whereUniqueId($deal['unique_id'])->first();

            if ($exists) {
                
                // Update deal
                $exists->update([
                    'isPending'  => false,
                    'isActive'   => true,
                    'isArchived' => false,
                ]);

                // Send notification to user
                JobDealApproved::dispatch($exists);

            }
    	}

    	// success
    	return response()->json([], 200);
    }
}
