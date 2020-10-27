<?php

namespace App\Http\Controllers\Administrator\Deals\Payments\Options;

use App\Http\Controllers\Controller;
use App\Model\ClassifiedPayment;
use Illuminate\Http\Request;

class DeclineController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-subscriptions');
    }



    /**
     * Decline selected payments
     * @param  Request           $request  [description]
     * @param  ClassifiedPayment $payments [description]
     * @return [type]                      [description]
     */
    public function decline(Request $request, ClassifiedPayment $payments)
    {
    	// Loop trough payments
    	foreach ($request->payments as $payment) {

    		// Decline payment
    		$payments->whereUniqueId($payment['unique_id'])
    				 ->where('isPending', true)
    				 ->where('isRefused', false)
    				 ->where('isAccepted', false)
    				 ->update([
						'isPending'  => false,
						'isAccepted' => false,
						'isRefused'  => true,
		    		]);

    		// Send notification

    	}

    	// Success
    	return response([]);
    }
}
