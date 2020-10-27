<?php

namespace App\Http\Controllers\Administrator\Fields\Options;

use App\Http\Controllers\Controller;
use App\Model\Field;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-fields');
    }


    /**
     * Restore selected deals
     * @param  Request $request [description]
     * @param  Field   $fields  [description]
     * @return [type]           [description]
     */
    public function restore(Request $request, Field $fields)
    {
    	// Loop trough fields
        foreach ($request->fields as $field) {
            $fields->whereUniqueId($field['unique_id'])->restore();
        }

        // Success
        return response([]);
    }
}
