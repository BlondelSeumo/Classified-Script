<?php

namespace App\Http\Controllers\Main\Account\Messages\Options;

use App\Http\Controllers\Controller;
use App\Model\Message;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Reply message
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function reply(Request $request)
    {
    	// Validate request
    	$request->validate([
    		'message' => 'required|max:500'
    	]);

    	// Get message
    	$message          = Message::whereUserTo(auth()->id())
    					  		   ->whereUniqueId($request->id)
    					  		   ->firstOrFail();

    	// Send reply
		$reply            = new Message;
		$reply->unique_id = uniqueId();
		$reply->user_from = auth()->id();
		$reply->user_to   = $message->user_from;
		$reply->deal_id   = $message->deal_id;
		$reply->message   = clean($request->message);
    	$reply->save();

    	// Send notification to user

    	// Success
    	return response([]);
    }
}
