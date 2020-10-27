<?php

namespace App\Http\Controllers\Administrator\Deals\Offers;

use App\Http\Controllers\Controller;
use App\Model\Offer;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class OffersController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-deals');
    }


    /**
     * Get recent offers
     * @return [type] [description]
     */
    public function offers()
    {
    	// Get offers
    	$offers = Offer::with('deal', 'from', 'to')->withTrashed()->latest()->paginate(50);

    	return response([
            'offers' => $offers,
            'seo'    => $this->seo()
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
