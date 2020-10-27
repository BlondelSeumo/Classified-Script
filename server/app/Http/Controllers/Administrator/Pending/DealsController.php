<?php

namespace App\Http\Controllers\Administrator\Pending;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Classified;

class DealsController extends Controller
{
    function __construct()
    {
    	$this->middleware('administrator');
        $this->authorize('admin-approve-deals');
    }


    /**
     * Get pending deals
     * @return [type] [description]
     */
    public function deals()
    {
    	// Pending deals
    	$deals = Classified::with('user', 'category')->where('isPending', true)->latest()->paginate(50);

    	return response($deals);
    }
}
