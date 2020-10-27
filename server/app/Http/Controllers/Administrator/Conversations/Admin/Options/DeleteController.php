<?php

namespace App\Http\Controllers\Administrator\Conversations\Admin\Options;

use App\Http\Controllers\Controller;
use App\Model\AdminMessage;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-conversations-admins');
    }


    /**
     * Delete selected messages
     * @param  Request      $request  [description]
     * @param  AdminMessage $messages [description]
     * @return [type]                 [description]
     */
    public function delete(Request $request, AdminMessage $messages)
    {
    	// Loop trough messages
        foreach ($request->messages as $message) {
            $messages->whereUniqueId($message['unique_id'])->delete();
        }

        // Success
        return response()->json([], 200);
    }
}
