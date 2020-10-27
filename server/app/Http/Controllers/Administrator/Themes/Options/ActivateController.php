<?php

namespace App\Http\Controllers\Administrator\Themes\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use ConfigWriter;

class ActivateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-edit-themes');
    }


    /**
     * Activate theme
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function activate(Request $request)
    {
    	// check if theme exists
    	if (!is_dir(resource_path('js/components/themes/' . $request->theme))) {
    		
    		// Not found
    		return response([], 422);

    	}
		
    	// Activate theme
        $config = new ConfigWriter('theme');
        $config->set('default', $request->theme);
        $config->save();

        // clear cache
        Artisan::call('config:clear');

        // success
    	return response([], 200);
    }
}
