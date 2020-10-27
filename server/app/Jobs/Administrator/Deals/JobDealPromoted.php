<?php

namespace App\Jobs\Administrator\Deals;

use App\Model\User;
use App\Model\Classified;
use Illuminate\Bus\Queueable;
use App\Model\ClassifiedPayment;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Administrator\Deals\DealPromoted;

class JobDealPromoted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $deal;

    protected $payment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Classified $deal, ClassifiedPayment $payment)
    {
        $this->deal    = $deal;
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get owners that can access users subscriptions
        $admins = User::with(['role' => function($q){
                            $q->where('group', '=', 'owner')
                            ->whereJsonContains('permissions', ['access_users_subscriptions' => true]);
                        }])->get();

        // Send them notification via email
        foreach ($admins as $admin) {
            $admin->notify(new DealPromoted($this->deal, $this->payment));
        }
    }
}
