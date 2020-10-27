<?php

namespace App\Http\Controllers\Administrator\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\SettingsGeneral;

class GeneralController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings', 'owner-access-settings-general');
    }


    /**
     * Get settings
     * @return [type] [description]
     */
    public function settings()
    {
    	// Get settings
		$settings  = SettingsGeneral::firstOrFail();
		
		// Get timezones
		$timezones = timezone_identifiers_list();

        // Show settings
        return response()->json([
			'settings'  => $settings,
			'timezones' => $timezones,
        ], 200); 
    }


    /**
     * Update settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Validate request
        $request->validate([
            'title'       => 'required|max:60',
            'tagline'     => 'max:60',
            'email'       => 'email|max:60',
            'timezone'    => 'required|max:60',
            'direction'   => 'required|boolean',
            'locale'      => 'required|max:2'
        ]);

        // Get settings
        $settings = SettingsGeneral::firstOrFail();

        // Update settings
        $settings->update([
            'title'    => request('title'),
            'tagline'  => request('tagline'),
            'email'    => request('email'),
            'timezone' => request('timezone'),
            'isRTL'    => request('direction'),
            'locale'   => request('locale')
        ]);

        // Change white header logo?
        if ($request->hasFile('white')) {
            $settings->uploadImage(request()->file('white'), 'whiteLogo');
        }

        // Change transparent header logo?
        if ($request->hasFile('transparent')) {
            $settings->uploadImage(request()->file('transparent'), 'transparentLogo');
        }

        // Change favicon logo?
        if ($request->hasFile('favicon')) {
            $settings->uploadImage(request()->file('favicon'), 'favicon');
        }

        // Success
        return response()->json([], 200);
    }
}
