<?php

namespace App\Http\Controllers\Main\Conversations\Chat;

use App\Http\Controllers\Controller;
use App\Model\Room;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * Room latest messages
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function latest(Request $request)
    {
    	// Validate room
		$roomId = $request->room;
		
		// Get online user
		$userId = auth()->id();
		
		// Check room
		$room   = Room::/*where(function($query) use ($userId) {
						$query->BySender($userId);
					})->orWhere(function($query) use ($userId) {
						$query->BySender($userId);
					})->*/where('unique_id', $roomId)->first();


		// Check room
		if (!$room) {
			// Error
			return response()->json($roomId, 422);
		}

		// Get messages
		$messages = $room->messages()->latest()->paginate(5);

		return response()->json([
			'messages' => $messages,
			'room'     => $room
		], 200); 
    }
}
