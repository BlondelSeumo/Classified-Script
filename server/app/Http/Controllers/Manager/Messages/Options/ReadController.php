<?php

namespace App\Http\Controllers\Manager\Messages\Options;

use App\Http\Controllers\Controller;
use App\Model\StoreMessage;
use Illuminate\Http\Request;

class ReadController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
    }


    /**
     * Read message
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function read(Request $request)
    {
    	// Check message
    	$message = StoreMessage::whereUniqueId($request->id)->where('isRead', false)->firstOrFail();

    	// Update message
    	$message->isRead = true;
    	$message->save();

    	// Success
    	return response([], 200);
    }
}
