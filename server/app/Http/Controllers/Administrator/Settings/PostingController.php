<?php

namespace App\Http\Controllers\Administrator\Settings;

use App\Http\Controllers\Controller;
use App\Model\SettingsPosting;
use Illuminate\Http\Request;

class PostingController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings');
    }



    /**
     * Get posting settings
     * @return [type] [description]
     */
    public function settings()
    {
    	// Get posting settings
    	$settings = SettingsPosting::find(1);

    	return response($settings);
    }



    /**
     * Update posting settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Vaidate request
    	$request->validate([
			'deals_per_day'       => 'required|integer',
			'deals_expires_after' => 'required|integer',
			'deals_max_images'    => 'required|integer'
    	]);

    	// Update settings
		$settings               = SettingsPosting::find(1);
		$settings->deals_day    = $request->deals_per_day;
		$settings->deals_life   = $request->deals_expires_after;
		$settings->deals_images = $request->deals_max_images;
    	$settings->save();

    	// Success
    	return response([]);
    }
}
