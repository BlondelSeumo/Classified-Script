<?php

namespace App\Http\Controllers\Main\Chat;

use App\Http\Controllers\Controller;
use App\Model\Room;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class ConversationsController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Get user conversations
     * @return [type] [description]
     */
    public function conversations(Request $request)
    {
    	// Get user id
    	$id            = auth()->id();

    	// Get user conversations
    	$conversations = Room::with('sender', 'receiver')
    						 ->where('sender_id', $id)
    						 ->orWhere('receiver_id', $id)
    						 ->latest()
                             ->get();
                             
        // Check if a conversation query present
        if ($request->conversation) {
            
            $current = Room::with('sender', 'receiver')
                           ->whereUniqueId($request->conversation)
    					   ->where('sender_id', $id)
    					   ->orWhere('receiver_id', $id)
                           ->firstOrFail();
                           
		    // Get current room messages
		    $messages = $current->messages()->oldest()->get();

        }else{

            // Not present
            $current  = null;
            $messages = [];

        }

    	return response([
            'conversations' => $conversations,
            'current'       => $current,
            'messages'      => $messages,
            'seo'           => $this->seo()
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
