<?php

namespace App\Http\Controllers\Main\Pages;

use App\Http\Controllers\Controller;
use App\Model\Page;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class PageController extends Controller
{

	/**
	 * Get a pag
	 * @param  [type] $slug [description]
	 * @return [type]       [description]
	 */
    public function page($slug)
    {
    	// Get page
    	$page = Page::whereSlug($slug)->select('title', 'content')->firstOrFail();

    	return response([
            'page' => $page,
            'seo'  => $this->seo($page)
        ]);
    }


    /**
     * Generate page seo
     * @return [type] [description]
     */
    private function seo($article)
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
        $description  = substr(strip_tags($seo->content), 0, 150);

        // Get logo
        if ($general->logo === null) {
            $image    = index('storage/app/uploads/default/settings/logo/logo.png');
        }else{
            $image    = index("storage/app/$general->logo");
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
