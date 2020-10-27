<?php

namespace App\Http\Controllers\Moderator\Pending;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-approve-comments');
    }



    /**
     * Get pending comments
     * @return [type] [description]
     */
    public function comments()
    {
    	// Get pending comments
    	$comments = Comment::with('user')->where('isPending', true)->latest()->paginate(50);

        return response($comments);
    }
}
