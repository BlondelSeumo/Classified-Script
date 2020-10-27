<?php

namespace App\Http\Controllers\Administrator\Deals\Payments;

use App\Http\Controllers\Controller;
use App\Model\ClassifiedPayment;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-subscriptions');
    }



    /**
     * Get deals payments
     * @return [type] [description]
     */
    public function payments()
    {
    	// Get payments
    	$payments = ClassifiedPayment::with('deal', 'package')->latest()->paginate(10);

    	return response($payments);
    }
}
