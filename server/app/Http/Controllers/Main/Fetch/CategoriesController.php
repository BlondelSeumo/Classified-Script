<?php

namespace App\Http\Controllers\Main\Fetch;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Category;

class CategoriesController extends Controller
{

	/**
	 * Get all categories
	 * @return [type] [description]
	 */
    public function categories()
    {
    	// Get categories
    	$categories = Category::whereParentId(false)->withCount('deals', 'childs')->latest()->get();

    	return response($categories);
    }



    /**
     * Get childs categories
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function childs(Request $request)
    {
    	// Get parent category
    	$parent = Category::whereId($request->id)->firstOrFail();

    	// Get childs
    	$childs = $parent->childs()->withCount('deals', 'childs')->latest()->get();

    	return response($childs);
    }
}
