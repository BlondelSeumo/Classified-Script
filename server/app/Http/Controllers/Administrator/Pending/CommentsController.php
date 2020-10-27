<?php

namespace App\Http\Controllers\Administrator\Pending;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-approve-comments');
    }


    /**
     * Get panding comments
     * @return [type] [description]
     */
    public function comments()
    {
    	$comments = Comment::with('user')->where('isPending', true)->latest()->paginate(50);

    	return response($comments);
    }
}
