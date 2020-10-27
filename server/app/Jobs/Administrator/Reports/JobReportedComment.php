<?php

namespace App\Jobs\Administrator\Reports;

use App\Model\User;
use App\Model\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Administrator\Reports\ReportedComment;

class JobReportedComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $comment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get owners
        $owners = User::with(['role' => function($q){
                            $q->where('group', '=', 'owner');
                        }])->get();

        // Get admins that can see reported comments
        $admins = User::with(['role' => function($q){
                            $q->where('group', '=', 'administrator')
                            ->whereJsonContains('permissions', ['reported_comments' => true]);
                        }])->get();

        // Get moderators that can see reported comments
        $moderators = User::with(['role' => function($q){
                                $q->where('group', '=', 'moderator')
                                ->whereJsonContains('permissions', ['reported_comments' => true]);
                            }])->get();

        // Send them notification via email to admins
        foreach ($admins as $admin) {
            $admin->notify(new ReportedComment($this->comment));
        }

        // Send them notification via email to admins
        foreach ($owners as $owner) {
            $owner->notify(new ReportedComment($this->comment));
        }

        // Send them notification via email to moderators
        foreach ($moderators as $moderator) {
            $moderator->notify(new ReportedComment($this->comment));
        }
    }
}
