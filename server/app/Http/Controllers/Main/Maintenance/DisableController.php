<?php

namespace App\Http\Controllers\Main\Maintenance;

use App\Http\Controllers\Controller;
use ConfigWriter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DisableController extends Controller
{

	protected $app;


	function __construct(Application $app)
	{
		$this->app = $app;
	}


	/**
	 * Disable maintenance mode
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function disable(Request $request)
    {
    	// check first if app in maintenance mode
    	if (!$this->app->isDownForMaintenance()) {
    		return redirect('/');
    	}

    	// Get token
    	$token = $request->token;

    	// Get saved token
    	$saved = config('maintenance.token');

    	// check if tokens matched
    	if ($token == $saved) {

    		// Disable maintenance mode
    		Artisan::call('up');

    		// Save changes
	        $maintenance = new ConfigWriter('maintenance');
            $maintenance->set('token', null);
	        $maintenance->save();

	        // Go to home page
	        return redirect('/');

    	}else{

    		// Not matched, go to home page
    		return redircet('/');

    	}
    }
}
