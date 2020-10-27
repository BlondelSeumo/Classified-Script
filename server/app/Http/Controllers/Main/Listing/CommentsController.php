<?php

namespace App\Http\Controllers\Main\Listing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Comment;
use App\Model\Classified;

class CommentsController extends Controller
{

	/**
	 * Get deal comments
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
    public function comments($id)
    {
		// Check if admin online
        if (auth()->check() && ( auth()->user()->isOwner() || auth()->user()->isAdministrator() || auth()->user()->isModerator() )) {

			// Get deal even it's not active
			$deal     = Classified::whereUniqueId($id)->firstOrFail();

		}else{

			// Deal must be active
			$deal     = Classified::whereUniqueId($id)->active()->firstOrFail();

		}
		
		// Get comments
		$comments = Comment::with('user')
						   ->where('isDeal', true)
						   ->wherePostId($deal->id)
						   ->active()
						   ->notSpam()
						   ->latest()
						   ->paginate(50);

		// Success
    	return response($comments);
    }
}
