<?php

namespace App\Search;

use Illuminate\Http\Request;
use App\Model\Product;

class ProductSearch
{

	/**
	 * Apply filter on search
	 * @param  Request $filters [description]
	 * @return [type]           [description]
	 */
    public static function apply(Request $filters)
    {
    	// Start new query
        $products = (new Product)->newQuery();

        // Get keyword
        $keyword = $filters->get('keyword');

        // Search for products based on keyword
        if ($filters->has('keyword')) {
            $products->where('slug', 'like', '%' . $keyword . '%')
            		 ->orWhere('title', 'like', '%' . $keyword . '%')
            		 ->orWhere('description', 'like', '%' . $keyword . '%')
            		 ->orWhereHas('category', function ($query) use ($keyword) {
				        $query->where('title', 'like', '%' . $keyword . '%');
				     });
        }
        
        // Get the results and return them.
        return $products->paginate(50);
    }
}
