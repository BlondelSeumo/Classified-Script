<?php

namespace App\Http\Controllers\Administrator\Support;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Administrator\MendelMan\ContactSupport;

class ContactController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-support');
    }


    /**
     * Send message to MendelManGroup
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function contact(Request $request)
    {
		// Make validation
		$request->validate([
			'name'     => 'required|max:30',
			'email'    => 'required|email|max:60',
			'priority' => 'required|in:low,medium,high,extra',
			'message'  => 'required'
		]);

		// Send message to developer
        // ezzaroual@mail.com is my email
        // Don't change it
        Mail::to('ezzaroual@mail.com')->send(new ContactSupport($request));

		// Success
		return response([]);
    }
}
