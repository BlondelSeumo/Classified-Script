<?php

namespace App\Http\Controllers\Administrator\Fetch;

use App\Http\Controllers\Controller;
use App\Model\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
    }


    /**
     * Get parent categories
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function categories(Request $request)
    {
    	// Get categories
        $categories = Category::whereParentId(false)->orderBy('title', 'desc')->get();

        return response()->json($categories, 200);
    }


    /**
     * Get all categories
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function all(Request $request)
    {
        // Get categories
        $categories = Category::latest('title')->get();

        return response()->json($categories, 200);
    }


    /**
     * Fetch child categories
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function childs(Request $request)
    {
    	// Validate request
		$request->validate([
			'parent' => 'required|exists:categories,id'
		]);	

		// Get category
		$category = Category::where('id', $request->parent)->first();

		// Check if has childs
		if (count($category->childs) > 0) {
			
			$response = array(
				'hasChilds' => true,
				'childs'    => $category->childs
			);

		}else{

			$response = array(
				'hasChilds' => false
			);

		}

		return response()->json($response, 200);
    }
}
