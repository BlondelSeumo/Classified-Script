<?php

namespace App\Http\Controllers\Administrator\Deals\Payments\Options;

use App\Http\Controllers\Controller;
use App\Model\Classified;
use App\Model\ClassifiedPayment;
use Illuminate\Http\Request;

class AcceptController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-subscriptions');
    }



    /**
     * Accept selected payments
     * @param  Request           $request  [description]
     * @param  ClassifiedPayment $payments [description]
     * @return [type]                      [description]
     */
    public function accept(Request $request, ClassifiedPayment $payments)
    {
    	// Loop trough payments
    	foreach ($request->payments as $payment) {

    		// Accept payment
    		$pay = $payments->whereUniqueId($payment['unique_id'])
    						->where('isPending', true)
    						->where('isAccepted', false)
    						->where('isRefused', false)
    						->first();

    		if ($pay) {
    			
    			// Update payment
    			$pay->update([
					'isPending'  => false,
					'isAccepted' => true,
					'isRefused'  => false,
	    		]);

	    		// Update deal
	    		$deal = Classified::whereId($pay->deal_id)->update([
	    			'isUpgraded' => true,
	    			'upgradedTo' => $pay->package->type,
	    			'expires_at' => now()->addDays($pay->package->days)
	    		]);

	    		// Send notificatio to user

    		}

    	}

    	// Success
    	return response([]);
    }
}
