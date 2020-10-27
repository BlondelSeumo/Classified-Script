<?php

namespace App\Jobs\Administrator\Pending;

use App\Model\User;
use App\Model\Classified;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Administrator\Pending\PendingDeal;

class JobPendingDeal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $deal;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Classified $deal)
    {
        $this->deal = $deal;
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

        // Get admins that can approve deals
        $admins = User::with(['role' => function($q){
                            $q->where('group', '=', 'administrator')
                            ->whereJsonContains('permissions', ['approve_classifieds' => true]);
                        }])->get();

        // Get moderators that can approve deals
        $moderators = User::with(['role' => function($q){
                                $q->where('group', '=', 'moderator')
                                ->whereJsonContains('permissions', ['approve_classifieds' => true]);
                            }])->get();

        // Send them notification via email to admins
        foreach ($admins as $admin) {
            $admin->notify(new PendingDeal($this->deal));
        }

        // Send them notification via email to admins
        foreach ($owners as $owner) {
            $owner->notify(new PendingDeal($this->deal));
        }

        // Send them notification via email to moderators
        foreach ($moderators as $moderator) {
            $moderator->notify(new PendingDeal($this->deal));
        }
    }
}
