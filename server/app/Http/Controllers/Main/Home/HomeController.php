<?php

namespace App\Http\Controllers\Main\Home;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Classified;
use App\Model\Product;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use App\Model\Store;
use Illuminate\Http\Request;

class HomeController extends Controller
{

	/**
	 * Home page
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function home(Request $request)
    {
    	// new deals query
    	$deals      = new Classified;

		// Get featured deals
		$featured   = $deals->with(['user' => function($query) {$query->select('id', 'username', 'avatar');}])
						  	->active()
						  	->featured()
						  	->inRandomOrder()
						  	->take(20)
						  	->get();

		// Get latest deals 
		$latest     = $deals->with(['user' => function($query) {$query->select('id', 'username', 'avatar');}])
							->latest()
							->active()
							->take(20)
							->get();
		
		// Stores
		$shops      = Store::active()->inRandomOrder()->take(20)->get();
		
		// Categories
		$categories = Category::withCount('deals', 'childs')->latest()->parent()->get();

		// Generate seo
		$seo        = $this->seo();

		// Return data
		return response()->json([
			'featured'   => $featured,
			'latest'     => $latest,
			'shops'      => $shops,
			'categories' => $categories,
			'seo'        => $seo
		], 200);
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
		
		// Generate tagline
		$tagline      = $general->tagline;
		
		// Get separator
		$separator    = $seo->separator;
		
		// Get description
		$description = $seo->description;

		// Get favicon
		$favicon      = $general->favicon === null ? index('storage/app/uploads/default/settings/favicon/favicon.png') : index("storage/app/$general->favicon");

		return [
			'title'       => $title,
			'tagline'     => $tagline,
			'separator'   => $separator,
			'description' => $description,
			'favicon'     => $favicon,
		];
    }
}
