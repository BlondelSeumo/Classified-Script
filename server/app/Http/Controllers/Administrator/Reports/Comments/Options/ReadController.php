<?php

namespace App\Http\Controllers\Administrator\Reports\Comments\Options;

use App\Model\PendingReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReadController extends Controller
{
    function __construct()
    {
        $this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-reported-comments');
    }


    /**
     * Mark selected reports as read
     * @param  Request         $request [description]
     * @param  PendingReport   $reports  [description]
     * @return [type]          [description]
     */
    public function read(Request $request, PendingReport $reports)
    {
    	// Loop trough reports
        foreach ($request->reports as $report) {

            // Mark report as read
            $reports->where('isComment', true)->whereUniqueId($report['unique_id'])->update([
                'isRead' => true
            ]);

        }

        // Success
        return response([]);
    }
}
