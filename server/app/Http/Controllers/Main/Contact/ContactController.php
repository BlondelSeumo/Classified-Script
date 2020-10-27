<?php

namespace App\Http\Controllers\Main\Contact;

use App\Model\SettingsSeo;
use App\Model\AdminMessage;
use Illuminate\Http\Request;
use App\Model\SettingsGeneral;
use App\Http\Controllers\Controller;
use App\Jobs\Administrator\Messages\JobNewMessage;

class ContactController extends Controller
{

	/**
     * Get page seo
     * @return [type] [description]
     */
    public function seo()
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

        // Get logo
        if ($general->logo === null) {
            $image = index('storage/app/uploads/default/settings/logo/logo.png');
        }else{
            $image = index("storage/app/$general->logo");
        }

        // Get favicon
        $favicon      = $general->favicon === null ? index('storage/app/uploads/default/settings/favicon/favicon.png') : index("storage/app/$general->favicon");

        return response([
            'title'       => $title,
            'separator'   => $separator,
            'description' => $description,
            'favicon'     => $favicon,
            'image'       => $image,
        ]);
    }


    
	/**
	 * Contact us
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function contact(Request $request)
    {
		// Make validation
		$request->validate([
			'name'    => 'required|max:60',
			'email'   => 'required|email|max:60',
			'subject' => 'required|max:100',
			'message' => 'required|max:10000'
		]);

		// Create message
		$message = AdminMessage::create([
			'unique_id' => uniqueId(),
			'name'      => $request->name,
			'email'     => $request->email,
			'subject'   => $request->subject,
			'message'   => $request->message,
			'ip'        => ip(),
        ]);
        
        // Send notification to admins
        JobNewMessage::dispatch($message);

		// Success
		return response()->json([], 200);
    }
}
