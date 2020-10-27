<?php

namespace App\Http\Controllers\Administrator\Conversations\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Room;
use App\Model\ChatMessage;

class MessagesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-conversations-chat');
    }



    /**
     * Get latest messages in a room
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function messages($id)
    {
    	// Get room
    	$room = Room::whereUniqueId($id)->firstOrFail();

    	// Get messages
    	$messages = ChatMessage::with('sender')->whereRoomId($room->id)->latest()->paginate(50);

    	// Success
    	return response($messages, 200);
    }
}
