<?php

namespace App\Http\Controllers\Administrator\Deals\Options;

use App\Http\Controllers\Controller;
use App\Model\Classified;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-deals');
    }


    /**
     * Restore deals from trash
     * @param  Request    $request [description]
     * @param  Classified $deals   [description]
     * @return [type]              [description]
     */
    public function restore(Request $request, Classified $deals)
    {
    	// Loop trough deals
    	foreach ($request->deals as $deal) {
    		$deals->whereUniqueId($deal['unique_id'])->update([
				'isPending'  => false,
				'isActive'   => true,
				'isArchived' => false,
    		]);
    		$deals->whereUniqueId($deal['unique_id'])->restore();
    	}

    	// success
    	return response()->json([], 200);
    }
}
