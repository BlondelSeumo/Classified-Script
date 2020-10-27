<?php

namespace App\Http\Controllers\Administrator\Settings;

use App\Http\Controllers\Controller;
use App\Model\SettingsPaymentGateways;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings', 'owner-access-settings-payments');
    }


    /**
     * Active/ Deactive payment gateways
     * @return [type] [description]
     */
    public function settings()
    {
        // Get settings
        $settings = SettingsPaymentGateways::find(1);

    	return response()->json($settings, 200);
    }


    /**
     * Update settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
        // Make validation
        $request->validate([
            'paypal' => 'required|boolean'
        ]);

        // Update settings
        SettingsPaymentGateways::where('id', 1)->update([
            'isPaypal' => $request->paypal
        ]);

        return response()->json([], 200);
    }
}
