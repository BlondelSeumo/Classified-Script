<?php

namespace App\Http\Controllers\Administrator\Pages\Options;

use App\Http\Controllers\Controller;
use App\Model\Page;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-pages');
    }


    /**
     * Delete selected pages
     * @param  Request    $request [description]
     * @param  Page $pages   [description]
     * @return [type]              [description]
     */
    public function delete(Request $request, Page $pages)
    {
    	// Loop trough pages
        foreach ($request->pages as $page) {
            $pages->whereSlug($page['slug'])->delete();
        }

        // Success
        return response()->json([], 200);
    }
}
