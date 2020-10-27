<?php

namespace App\Http\Controllers\Administrator\Categories\Options;

use App\Model\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-edit-categories');
    }



    /**
     * Get category to edit
     *
     * @param Request $request
     * @return void
     */
    public function edit(Request $request)
    {
        // Check categories
        $category = Category::whereSlug($request->slug)->firstOrFail();

        // Get parent categories
        $parents  = Category::parent()->latest('title')->get();

        return response([
            'category' => $category,
            'parents'  => $parents,
        ]);
    }


    public function update(Request $request)
    {
        // Check category
        $category = Category::whereSlug($request->slug)->firstOrFail();

        // Validate request
        $request->validate([
            'title'   => 'required|max:60',
            'slug'    => 'required|max:60|unique:categories,slug',
            'isChild' => 'required|boolean',
            'parent'  => 'nullable|exists:categories,id'
        ]);

        // Store data
    	$category->update([
			'parent_id' => request('parent') ?? $category->parent_id,
			'title'     => request('title'),
			'slug'      => request('slug')
    	]);

        // Icon
        if ($request->hasFile('icon')) {
            $category->uploadImage(request()->file('icon'), 'icon');
        }

        // Get categories
        $categories = Category::where('parent_id', 0)->latest('title')->get();

    	return response($categories);
    }
}
