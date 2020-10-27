<?php

namespace App\Http\Controllers\Manager\Messages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\StoreMessage;

class MessagesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
    }


    /**
     * Get manager, latest messages
     * @return [type] [description]
     */
    public function messages()
    {
    	// Get messages 
    	$messages = StoreMessage::with('shop', 'sender')->whereHas('shop', function($query) {
    		$query->whereUserId(auth()->id());
    	})->latest()->paginate(100);

    	return response($messages);
    }
}
