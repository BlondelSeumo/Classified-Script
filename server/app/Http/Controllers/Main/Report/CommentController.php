<?php

namespace App\Http\Controllers\Main\Report;

use App\Model\Comment;
use App\Model\PendingReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Administrator\Reports\JobReportedComment;

class CommentController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api']);
    }



    /**
     * Report comment
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function report(Request $request)
    {
    	// Check if comment exists
    	$comment = Comment::whereId($request->id)->firstOrFail();

    	// Don't report your comments please
    	if ($comment->user_id == auth()->id()) {
    		return response('error_yourself', 422);
    	}

    	// Already reported
    	$isReported = PendingReport::whereUserId(auth()->id())
    							   ->wherePostId($request->id)
    							   ->where('isComment', true)
    							   ->first();

    	// Already reported
    	if ($isReported) {
    		return response('error_reported', 422);
    	}

    	// Not reported, send report to admins, moderators, owners
		$report            = new PendingReport;
		$report->unique_id = uniqueId();
		$report->user_id   = auth()->id();
		$report->post_id   = $comment->id;
		$report->isComment = true;
		$report->save();
		
		// Send notification by email
		JobReportedComment::dispatch($comment);

    	// Success
    	return response([], 200);
    }
}
