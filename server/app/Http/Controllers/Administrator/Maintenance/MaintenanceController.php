<?php

namespace App\Http\Controllers\Administrator\Maintenance;

use ConfigWriter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Foundation\Application;
use App\Jobs\Administrator\Maintenance\JobMaintenance;

class MaintenanceController extends Controller
{

    protected $app;


    function __construct(Application $app)
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-maintenance');
        $this->app = $app;
    }


    /**
     * Show maintenance Settings
     * @return [type] [description]
     */
    public function settings()
    {
        // Generate settings
        $settings = array(
            'enabled' => $this->app->isDownForMaintenance(),
            'token'   => config('maintenance.token') 
        );

        // Show settings
    	return response()->json($settings, 200);
    }


    /**
     * Update Maintenance Settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
		// Make validation
        $request->validate([
            'enable' => 'required|boolean'
        ]);

        // Save changes
        $maintenance = new ConfigWriter('maintenance');

        // Check backup token
        if ($request->enable) {
            $maintenance->set('token', $request->token);
        }else{
            $maintenance->set('token', null);
        }

        $maintenance->save();

        // Clear cache
        Artisan::call('config:clear');

        // Down/Up application
        if ($request->enable) {

            // Send notification to admin
            if (auth()->id() != 1) {
                
                JobMaintenance::dispatch(true, auth()->user()->username);

            }

            Artisan::call('down');

        }else{

            Artisan::call('up');

            // Send notification to admin
            if (auth()->id() != 1) {
                
                JobMaintenance::dispatch(false, auth()->user()->username);

            }

        }

        // Success
        return response([], 200);
    }


    /**
     * Generate backup token
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function generate(Request $request)
    {
		if (!$this->app->isDownForMaintenance()) {
			
            // Generate backup token
            $token = Str::random(220);
            
            // Save Changes
            $footer = new ConfigWriter('maintenance');
            $footer->set('token', $token);
            $footer->save();

            // Clear cache
            Artisan::call('config:clear');

			return response()->json($token, 200);

		}else{
			return;
		}
    }
}
