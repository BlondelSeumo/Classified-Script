<?php

namespace App\Http\Controllers\Moderator\Deals\Options;

use App\Http\Controllers\Controller;
use App\Model\Classified;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-edit-deals');
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
    		$deals->whereUniqueId($deal['unique_id'])->update([
				'isPending'  => false,
				'isActive'   => false,
				'isArchived' => true,
    		]);
    	}

    	// success
    	return response([]);
    }
}
