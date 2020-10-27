<?php

namespace App\Console\Commands;

use App\Model\Classified;
use Illuminate\Console\Command;
use App\Jobs\Main\Deals\JobDealExpired;

class CommandExpiredDeals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deals:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change expired deals status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get expired deals
        $deals = Classified::with(['user' => function($query) {
                                $query->with('role');
                            }])->whereDate('expires_at', '<', now())->get();

        // Loop through deals
        foreach ($deals as $deal) {

            // Dont move admins and owners ads to archive
            // And keep them active forever
            if ($deal->user->role->group != 'owner' || $deal->user->role->group != 'administrator') {
                $deal->update([
                    'isUpgraded' => false,
                    'upgradedTo' => null,
                    'isArchived' => true,
                    'isActive'   => false
                ]);

                // Send notification
                JobDealExpired::dispatch($deal);
            }
            
        }
    }
}
