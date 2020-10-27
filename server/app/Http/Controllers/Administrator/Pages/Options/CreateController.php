<?php

namespace App\Http\Controllers\Administrator\Pages\Options;

use App\Http\Controllers\Controller;
use App\Model\Page;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-create-pages');
    }


    /**
     * Get footer config
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function fetch(Request $request)
    {
    	$config = array(
			'first'  => config('footer.rows.first'), 
			'second' => config('footer.rows.second'), 
			'third'  => config('footer.rows.third'), 
			'fourth' => config('footer.rows.fourth')
    	);

    	return response($config, 200);
    }


    /**
     * Insert new page in database
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Make validation
    	$request->validate([
			'title'   => 'required|max:100',
			'slug'    => 'required|max:30|alpha_dash|unique:pages,slug',
			'is_link' => 'boolean',
			'link'    => 'nullable|active_url',
            'column'  => 'required|in:first,second,third,fourth'
    	]);

    	// Save page
    	$page = Page::create([
            'title'         => $request->get('title'),
            'slug'          => $request->get('slug'),
            'content'       => $request->get('content'),
            'isLink'        => $request->get('is_link'),
            'link'          => $request->get('link'),
            'column'        => $request->get('column'),
    	]);

    	// Page created successfully
        return response()->json([], 200);
    }
}
