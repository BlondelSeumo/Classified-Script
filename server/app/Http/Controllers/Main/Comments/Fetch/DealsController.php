<?php

namespace App\Http\Controllers\Main\Comments\Fetch;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use Illuminate\Http\Request;

class DealsController extends Controller
{
	/**
	 * Get deals comments
	 * @return [type] [description]
	 */
    public function comments(Request $request)
    {
    	// Make validation
		$request->validate([
			'listing_id' => 'required|exists:classifieds,unique_id'
		]);

		// Get comments
		$comments = Comment::with('user')->wherePostUniqueId($request->listing_id)->where('isDeal', true)->latest()->paginate(50);

		return response()->json($comments, 200);
    }
}
