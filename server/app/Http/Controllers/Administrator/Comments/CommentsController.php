<?php

namespace App\Http\Controllers\Administrator\Comments;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-comments');
    }


    /**
     * Get latest comments
     * @return [type] [description]
     */
    public function comments()
    {
    	$comments = Comment::with('user', 'deal')->latest()->withTrashed()->paginate(10);

    	return response()->json($comments, 200);
    }
}
