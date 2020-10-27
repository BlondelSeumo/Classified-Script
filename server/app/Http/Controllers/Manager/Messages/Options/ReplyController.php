<?php

namespace App\Http\Controllers\Manager\Messages\Options;

use App\Http\Controllers\Controller;
use App\Model\StoreMessage;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
    }



    /**
     * Reply message
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function reply(Request $request)
    {
    	// Check message
    	$message = StoreMessage::whereUniqueId($request->id)->firstOrFail();

    	// Send reply
    	

    	// Update message
		$message->isRead    = true;
		$message->isReplied = true;
    	$message->save();

    	// Success
    	return response([]);
    }
}
