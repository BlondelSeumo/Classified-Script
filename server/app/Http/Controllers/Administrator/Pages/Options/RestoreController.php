<?php

namespace App\Http\Controllers\Administrator\Pages\Options;

use App\Http\Controllers\Controller;
use App\Model\Page;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-pages');
    }


    /**
     * Restore selected pages
     * @param  Request    $request [description]
     * @param  Page $pages   [description]
     * @return [type]              [description]
     */
    public function restore(Request $request, Page $pages)
    {
    	// Loop trough pages
        foreach ($request->pages as $page) {
            $pages->whereSlug($page['slug'])->restore();
        }

        // Success
        return response()->json([], 200);
    }
}
