<?php

namespace App\Http\Controllers\Administrator\Fetch;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use App\Model\Classified;
use App\Model\ClassifiedPayment;
use App\Model\PendingReport;
use App\Model\PendingStore;
use App\Model\User;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
    }



    /**
     * Count pending notifications
     * @return [type] [description]
     */
    public function notifications()
    {
    	// Get pending deals
		$deals            = Classified::where('isPending', true)->count();
		
		// Get pending comments
		$comments         = Comment::where('isPending', true)->count();
		
		// Get pending reported comments
		$reportedComments = PendingReport::where('isComment', true)->where('isRead', false)->count();

		// Get pending reported comments
		$reportedDeals    = PendingReport::where('isClassifieds', true)->where('isRead', false)->count();
		
		// Get pending shops
		$shops            = PendingStore::where('isRead', false)->count();
		
		// Get pending users
		$users            = User::where('isPending', true)->count();

		// Check if user has access to users subscriptions
		// To get payments niotifications
		$permissions      = json_decode(auth()->user()->role->permissions);

		// Check if owner
		if (auth()->user()->role->group == 'owner' && $permissions->access_users_subscriptions) {
			
			// "D"eals "P"ayments "H"istory "N"otifications
			$dphn = ClassifiedPayment::where('isPending', true)->count();

		}else{

			// "D"eals "P"ayments "H"istory "N"otifications
			$dphn = null;

		}

    	// Return response
    	return response([
			'deals'            => $deals,
			'comments'         => $comments,
			'reportedComments' => $reportedComments,
			'reportedDeals'    => $reportedDeals,
			'shops'            => $shops,
			'users'            => $users,
			'dphn'             => $dphn
    	]);
    }
}
