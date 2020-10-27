<?php

namespace App\Http\Controllers\Administrator\Conversations\Users\Options;

use App\Http\Controllers\Controller;
use App\Model\Message;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-conversations-users');
    }


    /**
     * Delete selected messages
     * @param  Request $request  [description]
     * @param  Message $messages [description]
     * @return [type]            [description]
     */
    public function delete(Request $request, Message $messages)
    {
    	// Loop trough messages
        foreach ($request->messages as $message) {
            $messages->whereUniqueId($message['unique_id'])->delete();
        }

        // Success
        return response()->json([], 200);
    }
}
