<?php

namespace App\Http\Controllers\Main\Account;

use App\Http\Controllers\Controller;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class DealsController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * Get user deals
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function deals(Request $request)
    {
    	// Get deals
    	$deals = auth()->user()->deals()->latest()->paginate(10);

    	// Return deals
    	return response([
            'deals' => $deals,
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
