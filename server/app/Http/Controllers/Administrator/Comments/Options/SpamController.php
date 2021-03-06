<?php

namespace App\Http\Controllers\Administrator\Comments\Options;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use Illuminate\Http\Request;

class SpamController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-comments');
    }



    /**
     * Mark selected comments as spam
     * @param  Request $request  [description]
     * @param  Comment $comments [description]
     * @return [type]            [description]
     */
    public function spam(Request $request, Comment $comments)
    {
    	// Loop trough comments
    	foreach ($request->comments as $comment) {
    		$comments->whereUniqueId($comment['unique_id'])->restore();
    		$comments->whereUniqueId($comment['unique_id'])->update([
				'isPending' => false,
				'isActive'  => false,
				'isSpam'    => true,
    		]);
    	}

    	// success
    	return response()->json([], 200);
    }
}
