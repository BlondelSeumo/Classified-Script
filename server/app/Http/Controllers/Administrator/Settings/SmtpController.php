<?php

namespace App\Http\Controllers\Administrator\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use ConfigWriter;

class SmtpController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings', 'owner-access-settings-smtp');
    }



    /**
     * Get smtp config settings
     * @return [type] [description]
     */
    public function settings()
    {
    	// Get smtp config
    	$config = array(
			'driver'     => config('mail.driver'), 
			'host'       => config('mail.host'), 
			'port'       => config('mail.port'), 
			'encryption' => config('mail.encryption'), 
			'username'   => config('mail.username'), 
			'email'      => config('mail.from.address'), 
			'name'       => config('mail.from.name')
    	);

    	return response()->json($config, 200);
    }


    /**
     * Update smtp settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
        // Validation
        $request->validate([
            'driver'     => 'required|in:log,smtp,sendmail',
            'host'       => 'required|max:100',
            'port'       => 'required|integer',
            'email'      => 'required|email|max:100',
            'name'       => 'required|max:100',
            'encryption' => 'required|in:tls,ssl',
            'username'   => 'required'
        ]);

        // Save changes
        $config = new ConfigWriter('mail');
        $config->set('driver', $request->driver);
        $config->set('host', $request->host);
        $config->set('port', $request->port);
        $config->set('from.address', $request->email);
        $config->set('from.name', $request->name);
        $config->set('encryption', $request->encryption);
        $config->set('username', $request->username);
        if ($request->password) {
            $config->set('password', $request->password);
        }
        $config->save();

        // clear cache
        Artisan::call('config:clear');

        // Success
        return response()->json([], 200);
    }
}
