<?php

namespace App\Http\Controllers\Main\Sitemap;

use App\Model\Store;
use App\Model\Classified;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SitemapController extends Controller
{

	/**
	 * Generate sitemap
	 * @return [type] [description]
	 */
    public function generate()
    {
		// Get shops
		$shops = Store::active()->latest()->get();
		
		// Get deals 
		$deals = Classified::active()->latest()->get();
		
    	return response([
    		'shops' => $shops,
    		'deals' => $deals
    	]);
    }
}
