<?php

namespace App\Http\Controllers\Administrator\Geolocation\States;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Khsing\World\Models\Division;

class StatesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-geo');
    }


    /**
     * Get states
     * @return [type] [description]
     */
    public function states()
    {
    	// Get states
		$states = Division::with('country')->orderBy('country_id', 'asc')->paginate(50);

		return response()->json($states, 200);
    }
}
