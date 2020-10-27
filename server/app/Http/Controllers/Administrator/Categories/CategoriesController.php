<?php

namespace App\Http\Controllers\Administrator\Categories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Category;

class CategoriesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-categories');
    }


    /**
     * Get recent categories
     * @return [type] [description]
     */
    public function categories()
    {
    	// Get categories
    	$categories = Category::with('childs', 'deals')->orderBy('id', 'desc')->paginate(50);

    	return response()->json($categories, 200);
    }
}
