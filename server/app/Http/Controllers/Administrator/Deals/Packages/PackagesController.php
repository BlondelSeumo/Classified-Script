<?php

namespace App\Http\Controllers\Administrator\Deals\Packages;

use App\Http\Controllers\Controller;
use App\Model\ClassifiedPackage;
use Illuminate\Http\Request;

class PackagesController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('owner-access-packages');
    }



    /**
     * Get packages
     * @return [type] [description]
     */
    public function packages()
    {
    	// Get deals packages
    	$packages = ClassifiedPackage::withTrashed()->latest()->paginate(50);

    	return response($packages);
    }
}
