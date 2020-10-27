<?php

namespace App\Http\Controllers\Main\Chat\Options;

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
     * Start new live chat
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function start(Request $request)
    {
    	// Check if user exists
    	$user = User::whereUsername($request->username)->active()->firstOrFail();

    	// Check if conversation already exists
    	$conversation = Room::where(function ($query) use ($user) {
						    	$query->where('sender_id', $user->id)
						          	  ->where('receiver_id', auth()->id());
						  })->orWhere(function ($query) use ($user) {
						    	$query->where('receiver_id', $user->id)
						              ->where('sender_id', auth()->id());
						  })->first();

		// Not found, create new
		if (!$conversation) {
    		$conversation = Room::create([
				'unique_id'   => uniqueId(),
				'sender_id'   => auth()->id(),
				'receiver_id' => $user->id
    		]);
    	}

    	return response($conversation->unique_id);
    }
}
