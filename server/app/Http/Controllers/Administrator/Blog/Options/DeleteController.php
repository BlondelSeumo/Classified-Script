<?php

namespace App\Http\Controllers\Administrator\Blog\Options;

use App\Http\Controllers\Controller;
use App\Model\Article;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-delete-articles');
    }


    /**
     * Delete selected articles
     * @param  Request $request  [description]
     * @param  Article $articles [description]
     * @return [type]            [description]
     */
    public function delete(Request $request, Article $articles)
    {
    	// Loop trough articles
        foreach ($request->articles as $article) {
            $articles->whereUniqueId($article['unique_id'])->delete();
        }

        // Success
        return response()->json([], 200);
    }
}
