<?php

namespace App\Http\Controllers\Main\Account\Search;

use App\Http\Controllers\Controller;
use App\Model\Search;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Get saved search by user
     * @return [type] [description]
     */
    public function saved()
    {
    	// Get saved search
    	$saved = Search::whereUserId(auth()->id())->latest()->paginate(50);

    	return response([
            'saved' => $saved,
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
