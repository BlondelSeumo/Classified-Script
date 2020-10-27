<?php

namespace App\Http\Controllers\Moderator\Comments;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-access-comments');
    }


    /**
     * Get latest comments
     * @return [type] [description]
     */
    public function comments()
    {
    	// Get comments
    	$comments = Comment::with(array(
    		'user' => function($query) {
    			$query->select('id', 'username');
    		},
    		'deal' => function($query) {
    			$query->select('id', 'photosNumber', 'title', 'unique_id', 'unique_slug');
    		}
        ))->withoutAdminComments()->withTrashed()->latest()->paginate(10);

    	return response($comments);
    }
}
