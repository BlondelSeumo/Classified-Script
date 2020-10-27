<?php

namespace App\Search;

use Illuminate\Http\Request;
use App\Model\Store;

class ShopSearch
{

	/**
	 * Apply filter on search
	 * @param  Request $filters [description]
	 * @return [type]           [description]
	 */
    public static function apply(Request $filters)
    {
    	// Start new query
        $shops = (new Store)->newQuery();

        // Get keyword
        $keyword = $filters->get('keyword');

        // Search for shops based on keyword
        if ($filters->has('keyword')) {
            $shops->where('store', 'like', '%' . $keyword . '%')
            		 ->orWhere('title', 'like', '%' . $keyword . '%')
                     ->orWhere('subtitle', 'like', '%' . $keyword . '%')
                     ->orWhere('description', 'like', '%' . $keyword . '%')
                     ->orWhere('address1', 'like', '%' . $keyword . '%')
            		 ->orWhere('address2', 'like', '%' . $keyword . '%');
        }
        
        // Get the results and return them.
        return $shops->paginate(50);
    }
}
