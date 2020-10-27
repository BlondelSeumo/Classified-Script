<?php

namespace App\Http\Controllers\Main\Search;

use App\Http\Controllers\Controller;
use App\Search\ShopSearch;
use Illuminate\Http\Request;

class ShopsController extends Controller
{

	/**
	 * Search in shops
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function search(Request $request)
    {
    	// Apply filter
    	return ShopSearch::apply($request);
    }
}
