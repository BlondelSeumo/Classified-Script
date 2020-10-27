<?php

namespace App\Http\Controllers\Moderator\Pending;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\PendingReport;

class ReportsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-reported-comments');
    }


    /**
     * Get reports
     * @return [type] [description]
     */
    public function reports()
    {
        // Get pending reports
        $reports = PendingReport::with('reporter', 'comment', 'deal')->where('isRead', false)->latest()->paginate(50);

        return response($reports);
    }


    /**
     * Mark selected reports as read
     * @param  Request       $request [description]
     * @param  PendingReport $reports [description]
     * @return [type]                 [description]
     */
    public function mark(Request $request, PendingReport $reports)
    {
    	// Loop trough reports
        foreach ($request->reports as $report) {
            $reports->whereUniqueId($report['unique_id'])->update([
            	'isRead' => true
            ]);
        }

        // Success
        return response([]);
    }
}
