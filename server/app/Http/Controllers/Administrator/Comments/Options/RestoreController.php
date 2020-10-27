<?php

namespace App\Http\Controllers\Administrator\Comments\Options;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-comments');
    }


    /**
     * Restore deleted comments
     * @param  Request $request  [description]
     * @param  Comment $comments [description]
     * @return [type]            [description]
     */
    public function restore(Request $request, Comment $comments)
    {
    	// Loop trough comments
    	foreach ($request->comments as $comment) {
    		$comments->whereUniqueId($comment['unique_id'])->update([
				'isPending' => false,
				'isActive'  => true,
				'isSpam'    => false,
    		]);
    		$comments->whereUniqueId($comment['unique_id'])->restore();
    	}

    	// success
    	return response()->json([], 200);
    }
}
