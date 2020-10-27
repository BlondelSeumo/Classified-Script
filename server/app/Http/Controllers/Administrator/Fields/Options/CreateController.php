<?php

namespace App\Http\Controllers\Administrator\Fields\Options;

use App\Http\Controllers\Controller;
use App\Model\Field;
use App\Model\FieldCategory;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-create-fields');
    }


    /**
     * Create new custom field
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate form
    	$request->validate([
			'name'         => 'required|max:60',
			'slug'         => 'required|max:60|alpha_dash|unique:custom_fields',
			'type'         => 'required|in:dropdown,radio,checkbox',
			'options'      => 'required_if:type,dropdown,radio',
			'required'     => 'required|boolean',
			'searchable'   => 'required|boolean',
			'categories'   => 'required|array',
			'categories.*' => 'required|exists:categories,id'
     	]);

    	// Create new custom field
    	$field = Field::create([
			'unique_id'    => uniqueId(),
			'name'         => $request->name,
			'slug'         => $request->slug,
			'type'         => $request->type,
			'options'      => $request->options,
			'isRequired'   => $request->required,
			'isSearchable' => $request->searchable
    	]);

    	// Save categories
    	$this->saveCategories($request->categories, $field->id);

    	// Success
    	return response()->json([], 200);
    }


    /**
     * Save categories
     * @param  [type] $categories [description]
     * @param  [type] $fieldId    [description]
     * @return [type]             [description]
     */
    private function saveCategories($categories, $fieldId)
    {
    	foreach ($categories as $category) {
    		FieldCategory::create([
    			'category_id' => $category,
    			'field_id'    => $fieldId
    		]);
    	}

    	return;
    }
}
