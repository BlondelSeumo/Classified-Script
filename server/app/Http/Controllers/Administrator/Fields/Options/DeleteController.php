<?php

namespace App\Http\Controllers\Administrator\Fields\Options;

use App\Http\Controllers\Controller;
use App\Model\Field;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-fields');
    }


    /**
     * Delete selected fields
     * @param  Request $request [description]
     * @param  Field   $fields  [description]
     * @return [type]           [description]
     */
    public function delete(Request $request, Field $fields)
    {
    	// Loop trough fields
        foreach ($request->fields as $field) {
            $fields->whereUniqueId($field['unique_id'])->delete();
        }

        // Success
        return response([]);
    }
}
