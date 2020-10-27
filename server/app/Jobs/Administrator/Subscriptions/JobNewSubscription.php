<?php

namespace App\Jobs\Administrator\Subscriptions;

use App\Model\User;
use App\Model\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Administrator\Subscriptions\NewSubscription;

class JobNewSubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subscription;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get owners that can access users subscriptions
        $owners = User::with(['role' => function($q){
                            $q->where('group', '=', 'owner')
                            ->whereJsonContains('permissions', ['access_users_subscriptions' => true]);
                        }])->get();

        // Send them notification via email to owners
        foreach ($owners as $owner) {
            $owner->notify(new NewSubscription($this->subscription));
        }
    }
}
