<?php

namespace App\Http\Controllers\Main\Category;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

	/**
	 * Browse deals by category
	 * @param  [type] $slug [description]
	 * @return [type]       [description]
	 */
    public function category($slug)
    {
    	// Check category
    	$category = Category::whereSlug($slug)->firstOrFail();

    	// Get deals
    	$deals    = $category->deals()->with('user')->active()->latest()->paginate(50);

    	// Get childs
    	$childs   = $category->childs()->latest()->get();

    	// Return 
    	return response([
			'category' => $category,
			'childs'   => $childs,
            'deals'    => $deals,
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
