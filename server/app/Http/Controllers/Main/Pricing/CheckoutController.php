<?php

namespace App\Http\Controllers\Main\Pricing;

use App\Http\Controllers\Controller;
use App\Model\Plan;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * Show checkout page
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function checkout($slug)
    {
    	// get plan
    	$plan = Plan::whereSlug($slug)->firstOrFail();

    	return response([
            'plan' => $plan,
            'seo'  => $this->seo()
        ]);
    }


    /**
     * Generate page seo
     * @return [type] [description]
     */
    private function seo()
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

        // Get favicon
        $favicon      = $general->favicon === null ? index('storage/app/uploads/default/settings/favicon/favicon.png') : index("storage/app/$general->favicon");

        return [
            'title'       => $title,
            'separator'   => $separator,
            'description' => $description,
            'favicon'     => $favicon
        ];
    }
}
