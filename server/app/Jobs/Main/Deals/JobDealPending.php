<?php

namespace App\Jobs\Main\Deals;

use App\Model\Classified;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Main\Deals\DealPending;

class JobDealPending implements ShouldQueue
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
        // Send notification
        $this->deal->user->notify(new DealPending($this->deal));
    }
}
