<?php

namespace App\Http\Controllers\Main\Listing;

use App\Model\Message;
use App\Model\Classified;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Main\Messages\JobNewMessage;

class ContactController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Send message about a deal
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function contact(Request $request)
    {
		// Check if admin online
        if (auth()->check() && ( auth()->user()->isOwner() || auth()->user()->isAdministrator() || auth()->user()->isModerator() )) {

			// Get deal even it's not active
			$deal     = Classified::whereUniqueId($request->id)->firstOrFail();

		}else{

			// Deal must be active
			$deal     = Classified::whereUniqueId($request->id)->active()->firstOrFail();

		}

    	// Your are not allowed to send message about your deals
    	if ($deal->user_id == auth()->id()) {
    		return response('not_allowed', 422);
    	}

    	// Make validation
    	$request->validate([
			'message' => 'required|min:6|max:500'
    	]);

    	// Send message
    	$message = Message::create([
			'unique_id' => uniqueId(),
			'deal_id'   => $deal->id,
			'user_from' => auth()->id(),
			'user_to'   => $deal->user_id,
			'message'   => clean($request->message)
    	]);

    	// Send notification by email
		JobNewMessage::dispatch($message);
		
    	// Success
    	return response([]);
    }
}
