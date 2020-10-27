<?php

namespace App\Http\Controllers\Moderator\Fetch;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use App\Model\Classified;
use App\Model\PendingReport;
use App\Model\PendingStore;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
    }


    /**
     * Count pending notifications
     * @return [type] [description]
     */
    public function notifications()
    {
		// Get pending deals
		if (auth()->user()->can('moderator-approve-deals')) {
			$deals    = Classified::where('isPending', true)->count();
		}else{
			$deals    = null;
		}
		
		// Get pending comments
		if (auth()->user()->can('moderator-approve-comments')) {
			$comments = Comment::where('isPending', true)->count();
		}else{
			$comments = null;
		}
		
		// Get pending reported comments
		if (auth()->user()->can('moderator-reported-comments')) {
			$reportedComments  = PendingReport::where('isComment', true)->where('isRead', false)->count();
		}else{
			$reportedComments  = null;
		}

		// Access pending reported deals
		if (auth()->user()->can('moderator-approve-deals')) {
			$reportedDeals    = PendingReport::where('isClassifieds', true)->where('isRead', false)->count();
		}else{
			$reportedDeals    = null;
		}
		
		// Get pending shops
		if (auth()->user()->can('moderator-approve-shops')) {
			$shops    = PendingStore::where('isRead', false)->count();
		}else{
			$shops    = null;
		}

    	// Return response
    	return response([
			'deals'            => $deals,
			'comments'         => $comments,
			'reportedDeals'    => $reportedDeals,
			'reportedComments' => $reportedComments,
			'shops'            => $shops
    	]);
    }
}
