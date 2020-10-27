<?php

namespace App\Http\Controllers\Administrator\Users\Options;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-users');
    }


    public function statistics(Request $request)
    {
		// Get user
		$user     = User::whereId($request->id)->firstOrFail();
		
		// Count total deals
		$deals    = $user->deals->count();
		
		// Count total shops
		$shops    = $user->shops->count();
		
		// Count total comments
		$comments = $user->comments->count();

    	// Show stats
    	return response([
			'deals'    => $deals,
			'shops'    => $shops,
			'comments' => $comments,
			'user_id'  => $user->id 
    	], 201);


    }
}
