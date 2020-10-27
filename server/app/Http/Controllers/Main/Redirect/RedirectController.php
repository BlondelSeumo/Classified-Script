<?php

namespace App\Http\Controllers\Main\Redirect;

use App\Http\Controllers\Controller;
use App\Model\Classified;
use App\Model\Product;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class RedirectController extends Controller
{

	/**
	 * Redirect to deal or product
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
    public function redirect($id)
    {
    	// Check deals
    	$deal = Classified::whereShortId($id)
                          ->where('isActive', true)
                          ->where('isPending', false)
                          ->firstOrFail();

      $seo  = $this->seo();

        // Return the unique slug
    	return response([
        'listing' => $deal->unique_slug,
        'seo'     => $seo
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
