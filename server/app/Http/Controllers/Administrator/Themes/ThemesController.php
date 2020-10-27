<?php

namespace App\Http\Controllers\Administrator\Themes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ThemesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-themes');
    }


    /**
     * Get available themes
     * @return [type] [description]
     */
    public function themes()
    {
    	// Get themes
    	$themes = config('theme');

    	return response($themes, 200);
    }
}
