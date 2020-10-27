<?php

namespace App\Http\Controllers\Main\Account\Search\Options;

use App\Http\Controllers\Controller;
use App\Model\Search;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Save new search
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Generate search link
    	$search      = 'search';

    	// check if keyword exists
    	if ($request->has('keyword')) {
    		$search .= '?q=' . $request->get('keyword');
    	}

    	// check if category or sub category exists
    	if ($request->get('child')) {
    		$search .= '&category=' . $request->get('child');
    	}else if ($request->has('category')) {
    		$search .= '&category=' . $request->get('category');
    	}

    	// check if sort exists
    	if ($request->has('sort')) {
    		$search .= '&sort=' . $request->get('sort');
    	}

    	// check if min price exists
    	if ($request->has('min')) {
    		$search .= '&min=' . $request->get('min');
    	}

    	// check if max price exists
    	if ($request->has('max')) {
    		$search .= '&max=' . $request->get('max');
    	}

    	// check if currency exists
    	if ($request->has('currency')) {
    		$search .= '&currency=' . $request->get('currency');
    	}

    	// Save search
    	$saved = Search::firstOrCreate(['user_id' => auth()->id(), 'search' => $search]);

    	// Success
    	return response([]);
    }
}
