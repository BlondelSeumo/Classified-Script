<?php

namespace App\Http\Controllers\Administrator\Settings;

use App\Http\Controllers\Controller;
use App\Model\Page;
use Artisan;
use ConfigWriter;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-settings', 'owner-access-settings-footer');
    }


    /**
     * Show settings
     * @return [type] [description]
     */
    public function settings()
    {
        // Get pages 
        $pages = Page::latest('title')->get();

        // Get newsletter
        $newsletter = config('newsletter');

        // Get config
        $config = config('footer');

        return response()->json([
			'pages'      => $pages,
			'newsletter' => $newsletter,
			'config'     => $config
        ], 200);
    }


    /**
     * Update settings
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    { 
        // Make validation
        $request->validate([
            'terms'        => 'required|exists:pages,slug',
            'privacy'      => 'required|exists:pages,slug',
            'firstColumn'  => 'required|max:100',
            'secondColumn' => 'required|max:100',
            'thirdColumn'  => 'required|max:100',
            'fourthColumn' => 'required|max:100',
        ]);

        // Save Footer Changes
        $footer     = new ConfigWriter('footer');
        $footer->set('rows.first', $request->firstColumn);
        $footer->set('rows.second', $request->secondColumn);
        $footer->set('rows.third', $request->thirdColumn);
        $footer->set('rows.fourth', $request->fourthColumn);
        $footer->set('copyrights', $request->copyrights);
        $footer->set('terms', $request->terms);
        $footer->set('privacy', $request->privacy);
        $footer->set('links.facebook', $request->facebook);
        $footer->set('links.twitter', $request->twitter);
        $footer->set('links.google', $request->google);
        $footer->set('links.instagram', $request->instagram);
        $footer->set('links.linkedin', $request->linkedin);
        $footer->set('links.tumblr', $request->tumblr);
        $footer->set('links.youtube', $request->youtube);
        $footer->set('links.vk', $request->vk);
        $footer->save();

        // Save newsletter Changes
        $newsletter = new ConfigWriter('newsletter');
        $newsletter->set('apiKey', $request->mailchimp_key);
        $newsletter->set('lists.subscribers.id', $request->mailchimp_id);
        $newsletter->save();

        // clear cache
        Artisan::call('config:clear');

        // Success
        return response()->json([], 200);
    }
}
