<?php

namespace App\Http\Controllers\Moderator\Deals\Options;

use App\Model\Classified;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Main\Deals\JobDealApproved;

class ActivateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-approve-deals');
    }


    /**
     * Activate pending deals
     * @param  Request $request [description]
     * @param  Classified   $deals   [description]
     * @return [type]           [description]
     */
    public function activate(Request $request, Classified $classified)
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

        // Success
        return response()->json([], 200);
    }
}
