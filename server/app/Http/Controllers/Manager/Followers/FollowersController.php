<?php

namespace App\Http\Controllers\Manager\Followers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Follower;

class FollowersController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
    }



    /**
     * Get followers
     * @return [type] [description]
     */
    public function followers()
    {
    	// Get followers
    	$followers = auth()->user()->followers()->with([
					    		'follower' => function($query) {
					    			$query->select('id', 'username');
					    		},
					    		'shop' => function($query) {
					    			$query->select('id', 'logo', 'store', 'title');
					    		}
							 ])
    						 ->latest()
    						 ->paginate(10);

    	return response($followers);
    }
}
