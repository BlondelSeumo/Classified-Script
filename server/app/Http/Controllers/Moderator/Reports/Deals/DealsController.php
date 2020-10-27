<?php

namespace App\Http\Controllers\Moderator\Reports\Deals;

use App\Model\PendingReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DealsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-access-deals');
    }


    /**
     * Get reported deals
     *
     * @return void
     */
    public function deals()
    {
        // Get reported deals
        $reports = PendingReport::with(['deal' => function($q) {
                                    $q->with('user')->withTrashed();
                                }, 'reporter'])->where('isClassifieds', true)
                                               ->where('isRead', false)
                                               ->latest()
                                               ->paginate(10);

        return response($reports);
    }
}
