<?php

namespace App\Http\Controllers\Main\Fetch;

use App\Http\Controllers\Controller;
use App\Model\Page;
use Illuminate\Http\Request;

class PagesController extends Controller
{
   	
   	/**
   	 * Get footer pages
   	 * @param  Request $request [description]
   	 * @return [type]           [description]
   	 */
    public function pages(Request $request)
    {
    	// Get pages
		$first  = Page::select('title as pageTitle','slug as pageSlug','isLink as isPageLink','link as pageLink')->where('column', 'first')->get();
		$second = Page::select('title as pageTitle','slug as pageSlug','isLink as isPageLink','link as pageLink')->where('column', 'second')->get();
		$third  = Page::select('title as pageTitle','slug as pageSlug','isLink as isPageLink','link as pageLink')->where('column', 'third')->get();
		$fourth = Page::select('title as pageTitle','slug as pageSlug','isLink as isPageLink','link as pageLink')->where('column', 'fourth')->get();
		$config = config('footer');

		return response()->json([
			'firstColumn'  => $first,
			'secondColumn' => $second,
			'thirdColumn'  => $third,
			'fourthColumn' => $fourth,
			'config'       => $config
		], 200);
    }
}
