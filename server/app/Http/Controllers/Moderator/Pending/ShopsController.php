<?php

namespace App\Http\Controllers\Moderator\Pending;

use App\Http\Controllers\Controller;
use App\Model\Store;
use Illuminate\Http\Request;

class ShopsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-access-shops');
    }


    /**
     * Get pending shops
     * @return [type] [description]
     */
    public function shops()
    {
    	// Get pending shops
    	$shops = Store::with('country')->where('isPending', true)->latest()->paginate(50);

    	return response($shops);
    }
}
