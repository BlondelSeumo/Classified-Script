<?php

namespace App\Http\Controllers\Administrator\Deals;

use App\Http\Controllers\Controller;
use App\Model\Classified;
use Illuminate\Http\Request;

class DealsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-deals');
    }


    /**
     * Get deals
     * @return [type] [description]
     */
    public function deals()
    {
    	// Get deals
        $deals = Classified::with('user', 'category')->withTrashed()->latest()->paginate(10);

    	return response()->json($deals, 200);
    }
}
