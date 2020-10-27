<?php

namespace App\Http\Controllers\Main\Chat\Options;

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
		// Validate request
		$request->validate([
			'message' => 'required|max:1000'
		]);

    	// Get room
    	$room    = Room::whereId($request->room)->firstOrFail();

    	// Create message
		$message = ChatMessage::create([
			'unique_id' => uniqueId(),
			'room_id'   => $request->room,
			'user_id'   => auth()->id(),
			'message'   => clean(strip_tags($request->message)),
			'sent_at'   => now()
		]);

		// Send event
		broadcast( new ChatEvent($message, $room->unique_id) )->toOthers();

		return response($message);
    }
}
