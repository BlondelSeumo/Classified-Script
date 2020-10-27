<?php

namespace App\Http\Controllers\Administrator\Settings;

use App\Http\Controllers\Controller;
use App\Model\SettingsUpload;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings', 'owner-access-settings-upload');
    }


    /**
     * Show settings
     * @return [type] [description]
     */
    public function settings()
    {
        // Get settings
        $settings = SettingsUpload::find(1);

    	return response()->json($settings, 200);
    }


    /**
     * Update settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
        // Make validation
        $request->validate([
            'max_image_size'        => 'required|integer|min:1',
            'max_total_images_size' => 'required|integer|min:1',
            'compression'           => 'required|boolean',
            'storage'               => 'required|in:local,amazon'
        ]);

        // Update settings
        SettingsUpload::where('id', 1)->update([
            'singleImageSize'   => $request->max_image_size,
            'multipleImageSize' => $request->max_total_images_size,
            'isCompression'     => $request->compression,
            'storage'           => $request->storage
        ]);

        // success
        return response()->json([], 200);	
    }
}
