<?php

namespace App\Http\Controllers\Administrator\Categories\Options;

use App\Http\Controllers\Controller;
use App\Model\Category;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-create-categories');
    }


    /**
     * Create new category
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate form
    	$request->validate([
            'title'   => 'required|max:60',
            'slug'    => 'required|max:60|unique:categories,slug',
            'isChild' => 'required|boolean',
            'parent'  => 'nullable|exists:categories,id'
    	]);

    	// Store data
    	$category = Category::create([
			'parent_id' => request('parent') ?? 0,
			'title'     => request('title'),
			'slug'      => request('slug')
    	]);

        // Icon
        if ($request->hasFile('icon')) {
            $category->uploadImage(request()->file('icon'), 'icon');
        }

        // Get categories
        $categories = Category::where('parent_id', 0)->orderBy('title', 'desc')->get();

    	return response()->json($categories, 200);
    }
}
