<?php

namespace App\Http\Controllers\Administrator\Conversations\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Message;

class MessagesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-conversations-users');
    }



    /**
     * Get latest users messages
     * @return [type] [description]
     */
    public function messages()
    {
    	// Get messages
    	$messages = Message::with('sender', 'receiver', 'deal')->latest()->paginate(100);

    	return response()->json($messages, 200);
    }
}
