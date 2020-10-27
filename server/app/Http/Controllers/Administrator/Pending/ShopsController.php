<?php

namespace App\Http\Controllers\Administrator\Pending;

use App\Http\Controllers\Controller;
use App\Model\Store;
use Illuminate\Http\Request;

class ShopsController extends Controller
{
    function __construct()
    {
    	$this->middleware('administrator');
        $this->authorize('admin-approve-shops');
    }


    /**
     * Get all pending shops
     * @return [type] [description]
     */
    public function shops()
    {
    	// Get pending shops
    	$shops = Store::with('country')->where('isPending', true)->latest()->paginate(50);

    	return response($shops);
    }
}
