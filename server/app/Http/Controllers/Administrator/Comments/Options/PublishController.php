<?php

namespace App\Http\Controllers\Administrator\Comments\Options;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use Illuminate\Http\Request;

class PublishController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-approve-comments');
    }


    /**
     * Publish pending comments
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function publish(Request $request, Comment $c)
    {
    	foreach ($request->comments as $comment) {
    		$c->whereUniqueId($comment['unique_id'])->update([
    			'isPending' => false,
    			'isActive'  => true
    		]);
    	}

    	// success
    	return response([], 201);
    }
}
