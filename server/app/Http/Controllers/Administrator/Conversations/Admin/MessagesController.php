<?php

namespace App\Http\Controllers\Administrator\Conversations\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AdminMessage;

class MessagesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-conversations-admins');
    }



    /**
     * Get latest messages to admin
     * @return [type] [description]
     */
    public function messages()
    {
    	// Get latest messages
    	$messages = AdminMessage::latest()->paginate(100);

    	return response()->json($messages, 200);
    }
}
