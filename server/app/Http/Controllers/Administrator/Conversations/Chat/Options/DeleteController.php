<?php

namespace App\Http\Controllers\Administrator\Conversations\Chat\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Room;
use App\Model\ChatMessage;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-conversations-chat');
    }



    /**
     * Delete rooms and messages permanently
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function delete(Request $request, Room $rooms)
    {
    	// Loop trough rooms
        foreach ($request->rooms as $room) {
        	// Delete room
            $rooms->whereUniqueId($room['unique_id'])->delete();
            // Delete messsage
            ChatMessage::whereRoomId($room['id'])->delete();
        }

        // Success
        return response()->json([], 200);
    }
}
