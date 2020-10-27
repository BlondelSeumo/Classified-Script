<?php

namespace App\Http\Controllers\Administrator\Pages\Options;

use App\Http\Controllers\Controller;
use App\Model\Page;
use Illuminate\Http\Request;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-edit-pages');
    }


    /**
     * Edit page
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {
    	// Get page
    	$page = Page::whereSlug($request->slug)->firstOrFail();

    	// Get config
    	$config = array(
			'first'  => config('footer.rows.first'), 
			'second' => config('footer.rows.second'), 
			'third'  => config('footer.rows.third'), 
			'fourth' => config('footer.rows.fourth')
    	);

    	// Response
    	return response([
			'page'   => $page,
			'config' => $config
    	], 200);
    }


    public function update(Request $request)
    {
    	// Get page
    	$page = Page::whereId($request->id)->firstOrFail();

    	// Make validation
    	$request->validate([
			'title'   => 'required|max:100',
			'slug'    => 'required|max:30|alpha_dash|unique:pages,slug,' . $page->id,
			'is_link' => 'boolean',
			'link'    => 'nullable|active_url',
            'column'  => 'required|in:first,second,third,fourth'
    	]);

    	// Update page
		$page->title   = $request->title;
		$page->slug    = $request->slug;
		$page->content = $request->content;
		$page->isLink  = $request->is_link;
		$page->link    = $request->link;
		$page->column  = $request->column;
    	$page->save();

    	// Page updated successfully
        return response()->json([], 200);
    }
}
