<?php

namespace App\Http\Controllers\Administrator\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Page;

class PagesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-pages');
    }



    /**
     * Get latest pages
     * [pages description]
     * @return [type] [description]
     */
    public function pages()
    {
    	// Get pages
    	$pages = Page::latest()->withTrashed()->paginate(100);

    	return response()->json($pages, 200);
    }
}
