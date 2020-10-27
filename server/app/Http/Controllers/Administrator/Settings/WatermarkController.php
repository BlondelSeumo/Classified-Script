<?php

namespace App\Http\Controllers\Administrator\Settings;

use App\Http\Controllers\Controller;
use App\Model\SettingsWatermark;
use Illuminate\Http\Request;

class WatermarkController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings', 'owner-access-settings-watermark');
    }


    /**
     * Show settings
     * @return [type] [description]
     */
    public function settings()
    {
        // Get settings
        $settings = SettingsWatermark::find(1);

    	return response($settings);
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
            'position' => 'required|in:top-left,top,top-right,left,center,right,bottom-left,bottom,bottom-right',
            'enabled'  => 'required|boolean',
        ]);

        // Get settings
        $settings = SettingsWatermark::find(1);

        // Update settings
        $settings->update([
            'position' => $request->position,
            'isActive' => $request->enabled
        ]);

        // Check if want to update watermark
        if ($request->hasFile('watermark')) {
            $settings->uploadImage(request()->file('watermark'), 'watermark');
        }

        // Success
        return response([]);
    }
}
