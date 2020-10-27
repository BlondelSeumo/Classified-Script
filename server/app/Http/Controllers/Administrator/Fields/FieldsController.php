<?php

namespace App\Http\Controllers\Administrator\Fields;

use App\Http\Controllers\Controller;
use App\Model\Field;
use App\Model\FieldCategory;
use Illuminate\Http\Request;

class FieldsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-fields');
    }


    /**
     * Get latest fields
     * @return [type] [description]
     */
    public function fields()
    {
    	// Get fields
    	$fields = Field::with('categories')->withTrashed()->latest('id')->paginate(100);

    	return response($fields);
    }
}
