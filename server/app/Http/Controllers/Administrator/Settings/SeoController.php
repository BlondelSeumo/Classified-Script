<?php

namespace App\Http\Controllers\Administrator\Settings;

use App\Http\Controllers\Controller;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings', 'owner-access-settings-seo');
    }


    /**
     * Show settings
     * @return [type] [description]
     */
    public function settings()
    {
        // Get settings
        $settings = SettingsSeo::firstOrFail();

        // Show settings
    	return response()->json($settings, 200);
    }


    /**
     * Update settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
        // Validate form
        $request->validate([
            'description' => 'max:250',
            'keywords'    => 'max:150',
            'separator'   => 'required|max:5',
            'sitemap'     => 'required|boolean'
        ]);

        // Update settings
        $settings = SettingsSeo::where('id', 1)->update([
            'description'       => $request->description,
            'keywords'          => $request->keywords,
            'separator'         => $request->separator,
            'dnsPrefetch'       => $request->dnsPrefetch,
            'isSitemap'         => $request->sitemap,
            'googleAnalyticsId' => $request->google_analytics
        ]);

        // Success
        return response()->json([], 200);
    }
}
