<?php

namespace App\Http\Controllers\Manager\Deals\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Classified;

class ArchiveController extends Controller
{
    
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
    }


    /**
     * Move deals to Archive
     * @param  Request    $request [description]
     * @param  Classified $deals   [description]
     * @return [type]              [description]
     */
    public function archive(Request $request, Classified $deals)
    {
    	// Loop trough deals
    	foreach ($request->deals as $deal) {
    		$deals->whereUserId(auth()->id())->whereUniqueId($deal['unique_id'])->update([
				'isPending'  => false,
				'isActive'   => false,
				'isArchived' => true,
    		]);
    	}

    	// success
    	return response()->json([], 200);
    }
}
