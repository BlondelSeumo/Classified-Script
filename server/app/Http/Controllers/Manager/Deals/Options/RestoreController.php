<?php

namespace App\Http\Controllers\Manager\Deals\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Classified;

class RestoreController extends Controller
{
    
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
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
    		$deals->whereUserId(auth()->id())->whereUniqueId($deal['unique_id'])->restore();
    	}

    	// success
    	return response()->json([], 200);
    }
}
