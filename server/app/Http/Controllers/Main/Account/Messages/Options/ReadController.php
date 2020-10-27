<?php

namespace App\Http\Controllers\Main\Account\Messages\Options;

use App\Http\Controllers\Controller;
use App\Model\Message;
use Illuminate\Http\Request;

class ReadController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Read message
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function read(Request $request)
    {
    	// Get message
    	$message = Message::where('isRead', false)
    					  ->whereUserTo(auth()->id())
    					  ->whereUniqueId($request->id)
    					  ->firstOrFail();

    	// Update message status
    	$message->isRead = true;
    	$message->save();

    	// Success
    	return response([]);
    }
}
