<?php

namespace App\Http\Controllers\Main\Pricing;

use App\Http\Controllers\Controller;
use App\Model\Plan;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class PricingController extends Controller
{

	/**
	 * Get plans
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function plans(Request $request)
    {
    	// Get available plans
    	$plans = Plan::oldest()->get();

    	return response([
            'plans' => $plans,
            'seo'   => $this->seo()
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

        // Get logo
        if ($general->logo === null) {
            $image = index('storage/app/uploads/default/settings/logo/logo.png');
        }else{
            $image = index("storage/app/$general->logo");
        }

        // Get favicon
        $favicon      = $general->favicon === null ? index('storage/app/uploads/default/settings/favicon/favicon.png') : index("storage/app/$general->favicon");

        return [
            'title'       => $title,
            'separator'   => $separator,
            'description' => $description,
            'favicon'     => $favicon,
            'image'       => $image,
        ];
    }
}
