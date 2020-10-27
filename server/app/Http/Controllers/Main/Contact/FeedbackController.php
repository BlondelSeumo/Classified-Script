<?php

namespace App\Http\Controllers\Main\Contact;

use App\Http\Controllers\Controller;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{

    /**
     * Get page seo
     * @return [type] [description]
     */
    public function seo()
    {
        // Get settings general
        $general      = SettingsGeneral::find(1);
        
        // Get seo settings
        $seo          = SettingsSeo::find(1);
        
        // Generate title
        $title        = $general->title;
        
        // Get separator
        $separator    = $seo->separator;
        
        // Get description
        $description  = $seo->description;

        // Get logo
        if ($general->logo === null) {
            $image = index('storage/app/uploads/default/settings/logo/logo.png');
        }else{
            $image = index("storage/app/$general->logo");
        }

        // Get favicon
        $favicon      = $general->favicon === null ? index('storage/app/uploads/default/settings/favicon/favicon.png') : index("storage/app/$general->favicon");

        return response([
            'title'       => $title,
            'separator'   => $separator,
            'description' => $description,
            'favicon'     => $favicon,
            'image'       => $image,
        ]);
    }


	/**
	 * Send feedback
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function feedback(Request $request)
    {
    	// Validate request
    	$request->validate([
    		'isSatisfied' => 'required|boolean',
    		'feedback'    => 'required|max:700'
    	]);

    	// Send feedback

    	// Success
    	return response()->json([], 200);
    }
}
