<?php

namespace App\Http\Controllers\Administrator\Conversations\Chat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Room;

class RoomsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-conversations-chat');
    }


    /**
     * Get latest chat rooms
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function rooms(Request $request)
    {
    	// Get rooms
    	$rooms = Room::with('sender', 'receiver')->latest()->paginate(50);

    	return response($rooms, 200);
    }
}
