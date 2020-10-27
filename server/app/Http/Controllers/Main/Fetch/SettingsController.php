<?php

namespace App\Http\Controllers\Main\Fetch;

use App\Http\Controllers\Controller;
use App\Model\Page;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    
    /**
     * Get general settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function settings(Request $request)
    {
    	// Get settings
        $settings = SettingsGeneral::find(1);
        
        // Get seo settings
        $seo      = SettingsSeo::find(1);
        
        // Get pages
        $first    = Page::select('title as pageTitle','slug as pageSlug','isLink as isPageLink','link as pageLink')->where('column', 'first')->get();
        $second   = Page::select('title as pageTitle','slug as pageSlug','isLink as isPageLink','link as pageLink')->where('column', 'second')->get();
        $third    = Page::select('title as pageTitle','slug as pageSlug','isLink as isPageLink','link as pageLink')->where('column', 'third')->get();
        $fourth   = Page::select('title as pageTitle','slug as pageSlug','isLink as isPageLink','link as pageLink')->where('column', 'fourth')->get();
        $config   = config('footer');
        
        // Get home background and text
        $home     = array(
            'wallpaper' => config('home.wallpaper'), 
            'text'      => config('home.text'), 
        );

		// Generate response
    	$response = array(
			'whiteLogo'    => $settings->whiteLogo, 
            'transLogo'    => $settings->transparentLogo,
            'firstColumn'  => $first,
            'secondColumn' => $second,
            'thirdColumn'  => $third,
            'fourthColumn' => $fourth,
            'config'       => $config,
            'home'         => $home,
            'title'        => $settings->title,
            'separator'    => $seo->separator,
            'description'  => $seo->description,
            'favicon'      => $settings->favicon,
            'rtl'          => $settings->isRTL,
    	);

    	// Return response
    	return response()->json($response, 200);
    }
}
