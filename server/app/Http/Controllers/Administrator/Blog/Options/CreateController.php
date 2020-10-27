<?php

namespace App\Http\Controllers\Administrator\Blog\Options;

use App\Model\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-create-articles');
    }


    /**
     * Create new article
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
    	// Validate
    	$request->validate([
			'title'   => 'required|max:100|unique:articles,title',
			'cover'   => 'image|mimes:png,jpg,jpeg|max:500',
			'content' => 'required'
    	]);

    	// Save article
    	$article = Article::create([
			'unique_id' => uniqueId(),
			'author_id' => auth()->id(),
			'title'     => $request->get('title'),
			'slug'      => Str::slug($request->get('title'), '-'),
			'content'   => $request->get('content'),
    	]);

        // Check if upload cover
        if ($request->hasFile('cover')) {
            $article->uploadImage(request()->file('cover'), 'cover');
        }

    	// Article saved successfully
    	return response([], 200);
    }
}
