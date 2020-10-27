<?php

namespace App\Http\Controllers\Administrator\Blog\Options;

use App\Model\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-edit-articles');
    }


    /**
     * Get article to edit
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit(Request $request)
    {
    	// check article
    	$article = Article::whereUniqueId($request->id)->firstOrFail();

    	// Return it
    	return response($article, 200);
    }


    public function update(Request $request)
    {
    	// Get article
    	$article = Article::whereUniqueId($request->id)->firstOrFail();

    	// Validate
    	$request->validate([
			'title'   => 'required|max:100|unique:articles,title,' . $article->id,
			'cover'   => 'nullable|image|mimes:png,jpg,jpeg|max:5000',
			'content' => 'required'
    	]);

    	// Update article
		$article->title   = $request->title;
		$article->slug    = Str::slug($request->title, '-');
		$article->content = $request->content;

        // Check if upload cover
        if ($request->hasFile('cover')) {
            $article->uploadImage(request()->file('cover'), 'cover');
        }

        // Save article
        $article->save();

    	// Article saved successfully
    	return response([], 200);
    }
}
