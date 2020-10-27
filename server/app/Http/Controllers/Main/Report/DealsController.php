<?php

namespace App\Http\Controllers\Main\Report;

use App\Model\Classified;
use App\Model\PendingReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Administrator\Reports\JobReportedDeal;

class DealsController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * Report deal
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function report(Request $request)
    {
    	// Check deal
    	$deal   = Classified::whereUniqueId($request->id)->active()->firstOrFail();

    	// Check if user is trying to report his own deal
    	if ($deal->user_id == auth()->id()) {
    		return response('error_yourself', 422);
    	}

    	// If user already report this deal
    	// Don't do anything
    	// Else Report deal
    	$report = PendingReport::firstOrCreate(
    			[
		    		'user_id' => auth()->id(),
		    		'post_id' => $deal->id,
		    		'isClassifieds' => true,
		    	], 
		    	[
		    		'unique_id' => uniqueId()
				]);
				
		// Send notification to admin
		JobReportedDeal::dispatch($deal);

    	// Success
    	return response([]);
    }
}
