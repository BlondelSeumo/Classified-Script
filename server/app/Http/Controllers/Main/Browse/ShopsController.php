<?php

namespace App\Http\Controllers\Main\Browse;

use App\Http\Controllers\Controller;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use App\Model\Store;
use Illuminate\Http\Request;

class ShopsController extends Controller
{

	/**
	 * Get latest active shops
	 * @return [type] [description]
	 */
    public function shops()
    {
    	// Get activate shops
    	$shops = Store::with(['country' => function($query) {
    					$query->select('id', 'code');
				      }])
    				  ->where('isActive', true)
    				  ->where('isPending', false)
    				  ->where('isExpired', false)
    				  ->inRandomOrder()
    				  ->paginate(50);

    	// Return shops
    	return response([
            'shops' => $shops,
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
