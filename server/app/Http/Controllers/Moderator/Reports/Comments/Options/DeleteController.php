<?php

namespace App\Http\Controllers\Moderator\Reports\Comments\Options;

use App\Model\PendingReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteController extends Controller
{
    function __construct()
    {
        $this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-delete-comments');
    }


    /**
     * delete selected reported deals
     * @param  Request         $request [description]
     * @param  PendingReport   $reports  [description]
     * @return [type]          [description]
     */
    public function delete(Request $request, PendingReport $reports)
    {
    	// Loop trough reports
        foreach ($request->reports as $report) {

            // Delete reported deal
            $exists = $reports->where('isComment', true)->whereUniqueId($report['unique_id'])->first();
            
            if ($exists) {

                $exists->comment->delete();

                // Mark it as read
                $exists->update([
                    'isRead' => true
                ]);
                
            }

        }

        // Success
        return response([]);
    }
}
