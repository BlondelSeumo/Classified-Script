<?php

namespace App\Http\Controllers\Administrator\Fields\Options;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Field;
use App\Model\FieldCategory;
use Illuminate\Http\Request;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-edit-fields');
    }



    /**
     * Get field to edit
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {
		// Check field
		$field      = Field::whereUniqueId($request->id)->with('categories')->firstOrFail();
		
		// Get categories
		$categories = Category::latest('title')->get();

    	return response([
			'field'      => $field,
			'categories' => $categories
    	]);
    }



    /**
     * Update custom field
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Check field
    	$field = Field::whereUniqueId($request->id)->firstOrFail();

    	// Make validation
    	$request->validate([
			'name'         => 'required|max:60',
			'slug'         => 'required|max:60|alpha_dash|unique:custom_fields,slug,' . $field->id,
			'type'         => 'required|in:dropdown,radio,checkbox',
			'options'      => 'required_if:type,dropdown,radio',
			'required'     => 'required|boolean',
			'searchable'   => 'required|boolean',
			'categories'   => 'required|array',
			'categories.*' => 'required|exists:categories,id'
     	]);

    	// Update field
    	$field->update([
			'name'         => $request->name,
			'slug'         => $request->slug,
			'type'         => $request->type,
			'options'      => $request->options,
			'isRequired'   => $request->required,
			'isSearchable' => $request->searchable
    	]);

    	// Save categories
    	$this->updateCategories($request->categories, $field->id);

    	// Success
    	return response([]);
    }


    /**
     * Update field categories
     * @param  [type] $categories [description]
     * @param  [type] $fieldId    [description]
     * @return [type]             [description]
     */
    private function updateCategories($categories, $fieldId)
    {
    	// Delete old categories for that field
    	$fields = FieldCategory::whereFieldId($fieldId)->delete();

    	foreach ($categories as $category) {
    		FieldCategory::create([
    			'category_id' => $category,
    			'field_id'    => $fieldId
    		]);
    	}

    	return;
    }
}
