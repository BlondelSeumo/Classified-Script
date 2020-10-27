<?php

namespace App\Http\Controllers\Main\Blog;

use App\Http\Controllers\Controller;
use App\Model\Article;
use App\Model\SettingsGeneral;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;

class BlogController extends Controller
{

	/**
	 * Get latest articles
	 * @return [type] [description]
	 */
    public function blog()
    {
    	// Get articles
    	$articles = Article::latest()->paginate(48);

    	return response([
            'articles' => $articles,
            'seo'      => $this->blogSeo()
        ]);
    }



    /**
     * Get article
     * @param  Request $request [description]
     * @param  [type]  $slug    [description]
     * @return [type]           [description]
     */
    public function article(Request $request, $slug)
    {
    	// Check article
    	$article = Article::whereSlug($slug)
    					  ->select('slug', 'title', 'content', 'cover', 'created_at')
    					  ->firstOrFail();

    	return response([
            'article' => $article,
            'seo'     => $this->articleSeo($article)
        ]);
    }



    /**
     * Generate page seo
     * @return [type] [description]
     */
    private function blogSeo()
    {
        // Get settings general
        $general      = SettingsGeneral::find(1);
        
        // Get seo settings
        $seo          = SettingsSeo::find(1);
        
        // Generate title
        $title        = $general->title;
        
        // Get separator
        $separator    = $seo->separator;
        
        // Get description
        $description  = $seo->description;

        // Get logo
        if ($general->logo === null) {
            $image = index('storage/app/uploads/default/settings/logo/logo.png');
        }else{
            $image = index("storage/app/$general->logo");
        }

        // Get favicon
        $favicon      = $general->favicon === null ? index('storage/app/uploads/default/settings/favicon/favicon.png') : index("storage/app/$general->favicon");

        return [
            'title'       => $title,
            'separator'   => $separator,
            'description' => $description,
            'favicon'     => $favicon,
            'image'       => $image,
        ];
    }



    /**
     * Generate page seo
     * @return [type] [description]
     */
    private function articleSeo($article)
    {
        // Get settings general
        $general      = SettingsGeneral::find(1);
        
        // Get seo settings
        $seo          = SettingsSeo::find(1);
        
        // Generate title
        $title        = $general->title;
        
        // Get separator
        $separator    = $seo->separator;
        
        // Get description
        $description  = substr(strip_tags($seo->description), 0, 150);

        // Get logo
        if ($article->cover === null) {
            $image    = index('storage/app/uploads/default/article/no-image.png');
        }else{
            $image    = index("storage/app/$article->cover");
        }

        // Get favicon
        $favicon      = $general->favicon === null ? index('storage/app/uploads/default/settings/favicon/favicon.png') : index("storage/app/$general->favicon");

        return [
            'title'       => $title,
            'separator'   => $separator,
            'description' => $description,
            'favicon'     => $favicon,
            'image'       => $image,
        ];
    }
}
