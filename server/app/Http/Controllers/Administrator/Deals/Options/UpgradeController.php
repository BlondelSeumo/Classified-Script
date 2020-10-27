<?php

namespace App\Http\Controllers\Administrator\Deals\Options;

use App\Http\Controllers\Controller;
use App\Model\Classified;
use Illuminate\Http\Request;

class UpgradeController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-edit-deals');
    }


    /**
     * Make selected deals featured, urgent or highlight
     * @param  Request    $request [description]
     * @param  Classified $deals   [description]
     * @return [type]              [description]
     */
    public function upgrade(Request $request, Classified $deals)
    {
    	// Check upgrade type
    	if (!in_array($request->upgradeTo, ['urgent', 'highlight', 'featured'])) {
    		return response([], 422);
    	}

    	// Loop trough deals
    	foreach ($request->deals as $deal) {
    		$deals->whereUniqueId($deal['unique_id'])->update([
				'isPending'  => false,
				'isActive'   => true,
				'isArchived' => false,
				'isUpgraded' => true,
				'upgradedTo' => $request->upgradeTo,
    		]);
    		$deals->whereUniqueId($deal['unique_id'])->restore();
    	}

    	// success
    	return response()->json([], 200);
    }
}
