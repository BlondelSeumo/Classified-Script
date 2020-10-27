<?php

namespace App\Http\Controllers\Administrator\Conversations\Chat\Options\Messages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\ChatMessage;
use App\Model\Room;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-conversations-chat');
    }


    /**
     * Delete messages
     * @param  Request     $request  [description]
     * @param  ChatMessage $messages [description]
     * @return [type]                [description]
     */
    public function delete(Request $request, ChatMessage $messages)
    {
    	// Check room
    	$room = Room::whereUniqueId($request->id)->firstOrFail();

    	// Loop trough messages
        foreach ($request->messages as $message) {
            $messages->whereRoomId($room->id)->whereUniqueId($message['unique_id'])->delete();
        }

        // Success
        return response()->json([], 200);
    }
}
