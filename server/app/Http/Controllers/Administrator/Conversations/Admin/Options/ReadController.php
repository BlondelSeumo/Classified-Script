<?php

namespace App\Http\Controllers\Administrator\Conversations\Admin\Options;

use App\Http\Controllers\Controller;
use App\Model\AdminMessage;
use Illuminate\Http\Request;

class ReadController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-conversations-admins');
    }


    /**
     * Read message
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function read(Request $request)
    {
    	// Check message
    	$message = AdminMessage::whereUniqueId($request->id)->where('isRead', false)->firstOrFail();

    	// Update message
    	$message->isRead = true;
    	$message->save();

    	// Success
    	return response([], 200);
    }
}
