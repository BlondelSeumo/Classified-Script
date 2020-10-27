<?php

namespace App\Http\Controllers\Administrator\Servies\Sms\Gateways;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use ConfigWriter;

class NexmoController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-sms');
    }


    /**
     * Get nexmo settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function settings(Request $request)
    {
    	// Get nexmo settings
    	$settings = array(
			'key'    => config('services.nexmo.key'), 
			'secret' => config('services.nexmo.secret'), 
			'from'   => config('services.nexmo.sms_from') 
    	);

    	return response($settings, 200);
    }


    /**
     * Update nexmo settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Make validaton
    	$request->validate([
			'key'    => 'required|max:60',
			'secret' => 'required|max:60',
			'from'   => 'required|max:60'
    	]);

    	// Rewrite settings
    	$config = new ConfigWriter('services');
        $config->set('nexmo.key', $request->key);
        $config->set('nexmo.secret', $request->secret);
        $config->set('nexmo.sms_from', $request->from);
        $config->save();

        // clear cache
        Artisan::call('config:clear');

        // Success
        return response([], 200);
    }
}
