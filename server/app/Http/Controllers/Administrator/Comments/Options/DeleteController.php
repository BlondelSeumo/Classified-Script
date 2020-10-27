<?php

namespace App\Http\Controllers\Administrator\Comments\Options;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-comments');
    }



    /**
     * Delete selected comments
     * @param  Request $request  [description]
     * @param  Comment $comments [description]
     * @return [type]            [description]
     */
    public function delete(Request $request, Comment $comments)
    {
    	// Loop trough comments
        foreach ($request->comments as $comment) {
            $comments->whereUniqueId($comment['unique_id'])->delete();
        }

        // Success
        return response()->json([], 200);
    }
}
