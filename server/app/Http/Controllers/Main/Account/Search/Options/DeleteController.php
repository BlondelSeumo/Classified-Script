<?php

namespace App\Http\Controllers\Main\Account\Search\Options;

use App\Http\Controllers\Controller;
use App\Model\Search;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    function __construct()
    {
    	$this->middleware('auth:api');
    }



    /**
     * Delete selected saved search
     * @param  Request $request [description]
     * @param  Search  $search  [description]
     * @return [type]           [description]
     */
    public function delete(Request $request, Search $search)
    {
    	// Loop trough search
        foreach ($request->search as $s) {
            $search->whereId($s['id'])->whereUserId(auth()->id())->delete();
        }

        // Success
        return response([]);
    }
}
