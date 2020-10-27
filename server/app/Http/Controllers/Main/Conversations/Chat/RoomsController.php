<?php

namespace App\Http\Controllers\Main\Conversations\Chat;

use App\Http\Controllers\Controller;
use App\Model\Room;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }


    /**
     * Get rooms
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function rooms(Request $request)
    {
    	// Get user id
		$id       = auth()->id();
		
		// Get rooms 
		$contacts = Room::with('sender', 'receiver')->where('sender_id', $id)->orWhere('receiver_id', $id)->get();

		return response([
            'contacts' => $contacts,
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
        $description  = $general->description;

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