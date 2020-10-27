<?php

namespace App\Http\Controllers\Main\Search;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Search\DealSearch;

class DealsController extends Controller
{

	/**
	 * Search in Deals
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function search(Request $request)
    {
    	// Apply filter
    	return DealSearch::apply($request);
    }
}
