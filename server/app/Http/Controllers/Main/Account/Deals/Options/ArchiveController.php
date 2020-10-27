<?php

namespace App\Http\Controllers\Main\Account\Deals\Options;

use App\Http\Controllers\Controller;
use App\Model\Classified;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Archive selected deals
     * @param  Request    $request [description]
     * @param  Classified $deals   [description]
     * @return [type]              [description]
     */
    public function archive(Request $request, Classified $deals)
    {
    	// Loop trough deals
        foreach ($request->deals as $deal) {
            $deals->whereUniqueId($deal['unique_id'])->where('isPending', false)->update([
            	'isActive'   => false,
            	'isArchived' => true
            ]);
        }

        // Success
        return response([]);
    }
}
