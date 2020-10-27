<?php

use App\Model\Room;


// Private chat
Broadcast::channel('chat.{roomId}', function ($user, $roomId) {

    // Check if online
    if (auth()->id()) {
        
        // Check room
        $room = Room::whereUniqueId($roomId)->first();

        if ($room && ($room->sender_id == $user->id || $room->receiver_id == $user->id)) {
            return true;
        }else{
            return false;
        }

    }else {
        
        // Not loggedin
        return false;

    }
});

// Pending comment
Broadcast::channel('pending-comment-{userId}', function($user, $userId) {

	if ($user->isAdministrator() || $user->isOwner()) {
		return true;
	}
	return false;

});