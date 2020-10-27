<?php

namespace App\Http\Controllers\Administrator\Currencies\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FetchController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-currencies');
    }


    /**
     * Get currencies codes
     * @return [type] [description]
     */
    public function fetch()
    {
    	// Get currencies codes
    	$config  = config('money');

    	// Get locales
    	$locales = config('locales');

    	// Success
    	return response([
    		'config'  => $config,
    		'locales' => $locales
    	], 200);
    }
}
