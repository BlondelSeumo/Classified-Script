<?php

namespace App\Http\Controllers\Moderator\Pending;

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
     * Get pending deals
     * @return [type] [description]
     */
    public function deals()
    {
    	// Get pending deals
        $deals = Classified::with('user', 'category')->where('isPending', true)->latest()->paginate(50);

    	// Return respnse
    	return response($deals);
    }
}
