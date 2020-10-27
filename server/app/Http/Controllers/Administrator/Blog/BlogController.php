<?php

namespace App\Http\Controllers\Administrator\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Article;

class BlogController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-blog');
    }


    /**
     * Get articles
     * @return [type] [description]
     */
    public function blog()
    {
    	$articles = Article::with('author')->withTrashed()->latest()->paginate(100);

    	return response($articles);
    }
}
