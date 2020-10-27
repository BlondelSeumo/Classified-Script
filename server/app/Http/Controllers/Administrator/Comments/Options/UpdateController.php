<?php

namespace App\Http\Controllers\Administrator\Comments\Options;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-edit-comments');
    }


    /**
     * Update comment
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Check comment
    	$comment = Comment::whereId($request->id)->firstOrFail();

    	// Make validation
    	$request->validate([
    		'comment' => 'required|max:500'
    	]);

    	// Update comment
    	$comment->comment = $request->comment;
    	$comment->save();

    	// Success
    	return response([], 200);
    }
}
