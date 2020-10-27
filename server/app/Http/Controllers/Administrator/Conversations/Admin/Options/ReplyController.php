<?php

namespace App\Http\Controllers\Administrator\Conversations\Admin\Options;

use App\Http\Controllers\Controller;
use App\Model\AdminMessage;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-conversations-admins');
    }



    /**
     * Reply message
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function reply(Request $request)
    {
    	// Check message
    	$message = AdminMessage::whereUniqueId($request->id)->firstOrFail();

    	// Send reply
    	

    	// Update message
		$message->isRead    = true;
		$message->isReplied = true;
    	$message->save();

    	// Success
    	return response([], 200);
    }
}
