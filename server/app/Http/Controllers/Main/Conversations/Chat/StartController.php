<?php

namespace App\Http\Controllers\Main\Conversations\Chat;

use App\Http\Controllers\Controller;
use App\Model\Room;
use App\Model\User;
use Illuminate\Http\Request;

class StartController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * Start new chat
     * @param  [type] $username [description]
     * @return [type]           [description]
     */
    public function start($username)
    {
    	// Check if user exists
    	$user = User::where('username', $username)->where('id', '!=', auth()->id())->first();

    	// User not exists or trying to chat with yourself
    	if (!$user) {
    		return response()->json('Account not found', 422);
    	}

    	// Check if room exists
    	$room = Room::where(function ($query) use ($user) {
						    $query->where('sender_id', $user->id)
						          ->where('receiver_id', auth()->id());
						})->orWhere(function ($query) use ($user) {
						    $query->where('receiver_id', $user->id)
						          ->where('sender_id', auth()->id());
						})->first();

		if (!$room) {

    		// create ne room
    		$room = Room::create([
				'unique_id'   => uniqueId(),
				'sender_id'   => auth()->id(),
				'receiver_id' => $user->id
    		]);

    	}

    	// return 
    	return response()->json($room->unique_id);
    }
}
