<?php

namespace App\Http\Controllers\Moderator\Home;

use App\Http\Controllers\Controller;
use App\Model\Classified;
use App\Model\Store;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
    }


    /**
     * Get moderator home page stats
     * @return [type] [description]
     */
    public function home()
    {

        // Get recent deals
        if (auth()->user()->can('moderator-access-deals')) {
            $deals = Classified::with('user', 'category')->latest()->take(10)->get();
        }else{
            $deals = null;
        }
        
        // Get recent shops
        if (auth()->user()->can('moderator-access-shops')) {
            $shops = Store::with('country')->latest()->take(10)->get();
        }else{
            $shops = null;
        }


    	// Return response
    	return response([
            'deals'    => $deals,
            'shops'    => $shops
    	]);
    }
}
