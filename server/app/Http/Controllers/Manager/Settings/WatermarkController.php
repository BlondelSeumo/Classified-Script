<?php

namespace App\Http\Controllers\Manager\Settings;

use App\Http\Controllers\Controller;
use App\Model\ManagerSettingsWatermark;
use Illuminate\Http\Request;

class WatermarkController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
    }


    /**
     * Show settings
     * @return [type] [description]
     */
    public function settings()
    {
        // Get settings
        $settings = ManagerSettingsWatermark::firstOrCreate(['user_id' => auth()->id()]);

    	return response()->json($settings, 200);
    }


    /**
     * Update settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Get settings
        $settings = ManagerSettingsWatermark::whereUserId(auth()->id())->firstOrFail();

        // Make validation
        $request->validate([
            'position' => 'required|in:top-left,top,top-right,left,center,right,bottom-left,bottom,bottom-right',
            'opacity'  => 'required|integer|between:1,100',
            'enabled'  => 'required|boolean',
        ]);

        // Update settings
        $settings->update([
            'position' => $request->position,
            'opacity'  => $request->opacity,
            'isActive' => $request->enabled
        ]);

        // Check if want to update watermark
        if ($request->hasFile('watermark')) {
            $settings->uploadImage(request()->file('watermark'), 'watermark');
        }

        // Success
        return response()->json([], 200);
    }
}
