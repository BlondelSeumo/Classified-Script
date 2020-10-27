<?php

namespace App\Jobs\Administrator\Pending;

use App\Model\User;
use App\Model\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Administrator\Pending\PendingShop;

class JobPendingShop implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $shop;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Store $shop)
    {
        $this->shop = $shop;
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

        // Get admins that can approve shops
        $admins = User::with(['role' => function($q){
                            $q->where('group', '=', 'administrator')
                            ->whereJsonContains('permissions', ['approve_stores' => true]);
                        }])->get();

        // Get moderators that can approve shops
        $moderators = User::with(['role' => function($q){
                            $q->where('group', '=', 'moderator')
                            ->whereJsonContains('permissions', ['approve_stores' => true]);
                        }])->get();

        // Send them notification via email to admins
        foreach ($admins as $admin) {
            $admin->notify(new PendingShop($this->shop));
        }

        // Send them notification via email to admins
        foreach ($owners as $owner) {
            $owner->notify(new PendingShop($this->shop));
        }

        // Send them notification via email to moderators
        foreach ($moderators as $moderator) {
            $moderator->notify(new PendingShop($this->shop));
        }
    }
}
