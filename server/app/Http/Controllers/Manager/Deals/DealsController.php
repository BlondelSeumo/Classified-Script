<?php

namespace App\Http\Controllers\Manager\Deals;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Classified;

class DealsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
    }



    /**
     * Get latest deals by manager
     * Current online manager :)
     * @return [type] [description]
     */
    public function deals()
    {
    	// Get deals by user
    	$deals = Classified::with(['category' => function($query) {
					    		$query->select('id', 'title');
					       }])
    					   ->whereUserId(auth()->id())
    					   ->latest()
    					   ->paginate(10);

    	return response($deals);
    }
}
