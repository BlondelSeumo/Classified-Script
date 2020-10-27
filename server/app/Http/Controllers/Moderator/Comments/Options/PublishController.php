<?php

namespace App\Http\Controllers\Moderator\Comments\Options;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use Illuminate\Http\Request;

class PublishController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-approve-comments');
    }



    /**
     * Activate pending comments
     * @param  Request $request [description]
     * @param  Comment   $comments   [description]
     * @return [type]           [description]
     */
    public function publish(Request $request, Comment $comments)
    {
    	// Loop trough comments
        foreach ($request->comments as $comment) {
            $comments->whereUniqueId($comment['unique_id'])->update([
            	'isActive' => true,
            	'isPending' => false
            ]);
        }

        // Success
        return response()->json([], 200);
    }
}
