<?php

namespace App\Http\Controllers\Main\Comments\Create;

use App\Model\Comment;
use App\Model\Classified;
use Illuminate\Http\Request;
use App\Model\SettingsSecurity;
use App\Http\Controllers\Controller;
use App\Jobs\Main\Comments\JobNewComment;
use ConsoleTVs\Profanity\Facades\Profanity;
use App\Jobs\Administrator\Pending\JobPendingComment;

class DealsController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
        $this->authorize('member-write-comments');
    }


    /**
     * Create new comment
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
		// Make validation
		$request->validate([
			'comment'    => 'required|max:500'
		]);

		// Get deal
		$deal = Classified::whereUniqueId($request->listing_id)->active()->firstOrFail();

		// Get comment status
		$status = $this->status(clean($request->comment));

		// Create comment
		$comment = Comment::create([
			'unique_id'      => uniqueId(),
			'user_id'        => auth()->id(),
			'post_id'        => $deal->id,
			'post_unique_id' => $deal->unique_id,
			'comment'        => Profanity::blocker($request->comment)->filter(),
			'isClassifieds'  => true,
			'isActive'       => $status['isActive'],
			'isPending'      => $status['isPending'],
			'isSpam'         => $status['isSpam']
		]);

		// Send notification to admin
		JobPendingComment::dispatch($comment);

		// Send notification to user
		JobNewComment::dispatch($comment, $deal);

		// Success
		return response($comment->load('user'));
    }



    /**
     * Check if comments requires approval
     * @return [type] [description]
     */
    private function status($comment)
    {
    	// Get online user
    	$user = auth()->user();

    	// Admin && moderator && owner
    	if ($user->isAdministrator() || $user->isOwner() || $user->isModerator()) {
    		return array(
				'isPending' => false,
				'isSpam'    => false,
				'isActive'  => true
    		);
    	}

    	// Get settings
    	$settings = SettingsSecurity::find(1);

    	// Check if spam detect enable
    	if ($settings->spamFilter) {
    		
    		// Enabled, check if comment has bad words
    		if (count(Profanity::blocker($comment)->badWords())) {
    			return array(
					'isPending' => true,
					'isSpam'    => true,
					'isActive'  => false
	    		);
    		}

    	}

    	// Requires approval
    	if (!$settings->autoApproveComments) {
    		return array(
				'isPending' => true,
				'isSpam'    => false,
				'isActive'  => false
    		);
    	}

    	// No action needed, approve the comment
    	return array(
			'isPending' => false,
			'isSpam'    => false,
			'isActive'  => true
		);
    }
}
