<?php

namespace App\Http\Controllers\Administrator\Support;

use ConfigWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-support');
    }


    /**
     * Get support settings
     * @return [type] [description]
     */
    public function settings()
    {
    	// Get config
    	$config = config('envato');

    	return response()->json($config, 200);
    }


    /**
     * Update support settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	// Make validation
		$request->validate([
			'code'     => 'required|min:36|max:36',
			'username' => 'required'
		]);


		// Save changes
		$footer     = new ConfigWriter('envato');
        $footer->set('purchaseCode', $request->code);
        $footer->set('envatoUsername', $request->username);
        $footer->save();

        // Clear cache
        Artisan::call('config:clear');

		// Success
		return response()->json([], 200);
    }
}
