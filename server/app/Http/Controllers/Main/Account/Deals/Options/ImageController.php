<?php

namespace App\Http\Controllers\Main\Account\Deals\Options;

use App\Http\Controllers\Controller;
use App\Model\Classified;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Delete image from deal
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function delete(Request $request)
    {
    	// Check deal
    	$deal    = Classified::whereUniqueId($request->id)->whereUserId(auth()->id())->firstOrFail();

    	// Get upload path
    	$path    = storage_path('app/uploads/classifieds/' . $deal->unique_id);

    	// Get image path
    	$image   = $path . '/' . $request->image;

    	// Check if image exists
    	if (!file_exists($image)) {
    		return abort(404);
    	}else{
    		// Delete image
    		unlink($image);
    	}

    	// Rename && update photos
    	$counter = $this->rename($deal);

    	// Update photos number

    	// Success, return images counter
    	return response($counter);
    }



    /**
     * Rename images after delete
     * @param  Classified $deal [description]
     * @return [type]           [description]
     */
    private function rename(Classified $deal)
    {
    	// Get directory
		$directory = storage_path("app\uploads\classifieds\\" . $deal->unique_id . '\\');
		
		// Get images in this directory
		$images    = glob($directory . "*.jpg");
		
		// Sort images to rename them
		$sort      = array();

		foreach ($images as $key => $row)
		{
		    $sort[$key] = $row;
		}

		array_multisort($sort, SORT_NATURAL, $images);
		 
		// Rename photos
	 	foreach($sort as $index => $image)
		{
			rename($image, $directory . 'preview_' . $index . '.jpg');
		}

		// Update photos number
		$deal->photosNumber = count($images);
		$deal->save();

		// Return total images
		return count($images);
    }
}
