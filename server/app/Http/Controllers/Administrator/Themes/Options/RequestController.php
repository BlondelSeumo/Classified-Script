<?php

namespace App\Http\Controllers\Administrator\Themes\Options;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\Administrator\Themes\JobRequestTheme;

class RequestController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-request-themes');
    }


    /**
     * Request new theme
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function request(Request $request)
    {
    	// Validate form
    	$request->validate([
            'name'    => 'required|max:100',
            'email'   => 'required|email|max:60',
            'example' => 'required',
            'budget'  => 'required',
            'details' => 'required',
            'unique'  => 'required|boolean'
    	]);
        
        // Send message to developer
        // ezzaroual@mail.com is my email
        // Don't change it
        JobRequestTheme::dispatch($request->all());

        // Success
        return response([]);
    }
}
