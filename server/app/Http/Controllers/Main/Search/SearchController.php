<?php

namespace App\Http\Controllers\Main\Search;

use App\Http\Controllers\Controller;
use App\Search\DealSearch;
use Illuminate\Http\Request;

class SearchController extends Controller
{

	/**
	 * Search in deals
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function search(Request $request)
    {
    	// Apply filter
    	return DealSearch::apply($request);
    }
}
