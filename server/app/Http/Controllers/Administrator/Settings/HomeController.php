<?php

namespace App\Http\Controllers\Administrator\Settings;

use App\Http\Controllers\Controller;
use ConfigWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Image;

class HomeController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings');
    }


    /**
     * Get home settings
     * @return [type] [description]
     */
    public function settings()
    {
    	// Return home text
    	// Wallpaper is optional while updating
    	return response(config('home.text'));
    }



    /**
     * Update home settings
     * @param  Request $rquest [description]
     * @return [type]          [description]
     */
    public function update(Request $request)
    {
    	// Get home config file
    	$config = new ConfigWriter('home');

    	// Check if request has wallpaper file
    	if ($request->hasFile('wallpaper')) {
    		
    		// Upload wallpaper
    		$wallpaper = $this->wallpaper($request);

    		$config->set('wallpaper', $wallpaper);

    	}

    	// Save text
        $config->set('text', $request->text);
        $config->save();

        // clear cache
        Artisan::call('config:clear');

        // Success
        return response([]);
    }


    /**
     * Save wallpaper
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    private function wallpaper(Request $request)
    {
    	// Get path to save wallpaper
		$path      = storage_path('app/uploads/settings/wallpaper');
		
		// Clear this path
		$this->clear($path);
		
		// Generate a unique id for this wallpaper
		$unique    = uniqueId(45);
		
		// start uploading
		$wallpaper = Image::make($request->file('wallpaper'));
        $wallpaper->save($path . '/' . $unique . '.jpg', 60);

        // Success, return the unique id
        return $unique;
    }


    /**
     * Delete all files from a path
     * @param  [type] $directory [description]
     * @return [type]            [description]
     */
    private function clear($directory)
    {
    	// Get all files in this directory
		$files = glob($directory . '/*');
		 
		foreach($files as $file){
		    // If this is a file delete it
		    if(is_file($file)){
		        unlink($file);
		    }
		}
    }
}
