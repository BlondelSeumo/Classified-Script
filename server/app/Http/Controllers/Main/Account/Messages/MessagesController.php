<?php

namespace App\Http\Controllers\Main\Account\Messages;

use App\Http\Controllers\Controller;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Get messages 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function messages(Request $request)
    {
    	// Get user messages
    	$messages = auth()->user()->conversations()->with(array('sender' => function($query) {
    		$query->select('id', 'username');
    	}, 'deal' => function($query) {
    		$query->select('id', 'unique_slug', 'unique_id', 'title', 'photosNumber');
    	}))->latest()->paginate(50);

    	return response([
            'messages' => $messages,
            'seo'      => $this->seo()
        ]);
    }


    /**
     * Generate page seo
     * @return [type] [description]
     */
    private function seo()
    {
        // Get settings general
        $general      = SettingsGeneral::find(1);
        
        // Get seo settings
        $seo          = SettingsSeo::find(1);
        
        // Generate title
        $title        = $general->title;
        
        // Get separator
        $separator    = $seo->separator;
        
        // Get description
        $description  = $seo->description;

        // Get favicon
        $favicon      = $general->favicon === null ? index('storage/app/uploads/default/settings/favicon/favicon.png') : index("storage/app/$general->favicon");

        return [
            'title'       => $title,
            'separator'   => $separator,
            'description' => $description,
            'favicon'     => $favicon
        ];
    }
}
