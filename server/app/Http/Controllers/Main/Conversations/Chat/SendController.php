<?php

namespace App\Http\Controllers\Main\Conversations\Chat;

use App\Events\ChatEvent;
use App\Http\Controllers\Controller;
use App\Model\ChatMessage;
use App\Model\Room;
use Illuminate\Http\Request;

class SendController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * Send message
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function send(Request $request)
    {
    	// Check if room exists
    	$room = Room::find($request->room);

    	if (! $room->exists()) {
    		return response()->json([], 422);
    	}

    	// Create message
		$message = ChatMessage::create([
			'unique_id' => uniqueId(),
			'room_id'   => $request->room,
			'user_id'   => auth()->id(),
			'message'   => clean(strip_tags($request->message)),
			'sent_at'   => now()
		]);

		// Send event
		broadcast( new ChatEvent($message, auth()->user(), $room->unique_id) );

		return response()->json($message, 200);
    }
}
