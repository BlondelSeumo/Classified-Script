<?php

namespace App\Http\Controllers\Administrator\Reports\Comments;

use App\Model\PendingReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-reported-comments');
    }


    /**
     * Get reported comments
     *
     * @return void
     */
    public function comments()
    {
        // Get reported comments
        $reports = PendingReport::with(['comment' => function($q) {
                                    $q->with('user')->withTrashed();
                                }, 'reporter'])->where('isComment', true)
                                               ->where('isRead', false)
                                               ->latest()
                                               ->paginate(10);

        return response($reports);
    }
}
