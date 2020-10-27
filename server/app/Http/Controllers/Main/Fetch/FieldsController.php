<?php

namespace App\Http\Controllers\Main\Fetch;

use App\Http\Controllers\Controller;
use App\Model\FieldCategory;
use Illuminate\Http\Request;

class FieldsController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * Get fields in category
     * @param  Resquest $request [description]
     * @return [type]            [description]
     */
    public function fields(Request $request)
    {
    	// Validate request
    	$request->validate([
    		'category' => 'required|integer|exists:categories,id'
    	]);

    	// Get fields
    	$fields = FieldCategory::with('field')->whereCategoryId($request->category)->get();

    	return response($fields);
    }
}
