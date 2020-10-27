<?php

namespace App\Http\Controllers\Manager\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Subscription;

class SubscriptionController extends Controller
{
    public function __construct() {
        $this->middleware(['auth:api', 'manager']);
    }

    
    /**
     * Get user subscripton
     *
     * @param Request $request
     * @return void
     */
    public function subscription()
    {
        // Get user subscription
        $subscription = Subscription::whereUserId(auth()->id())->with('plan')->latest()->paginate(50);

        return response($subscription);
    }
}
