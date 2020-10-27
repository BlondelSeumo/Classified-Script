<?php

namespace App\Http\Controllers\Moderator\Deals;

use App\Http\Controllers\Controller;
use App\Model\Classified;
use Illuminate\Http\Request;

class DealsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-access-deals');
    }


    /**
     * Get latest deals
     * @return [type] [description]
     */
    public function deals()
    {
    	// Get deals
    	$deals = Classified::with('user', 'category')->isNotAdminDeals()->withTrashed()->latest()->paginate(10);

    	return response($deals);
    }
}
