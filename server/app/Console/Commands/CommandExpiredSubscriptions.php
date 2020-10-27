<?php

namespace App\Console\Commands;

use App\Model\Store;
use App\Model\Subscription;
use Illuminate\Console\Command;
use App\Notifications\Manager\Shops\ShopExpired;

class CommandExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check expired subscriptions';

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
        // Get subscriptions
        $subscriptions = Subscription::with(['user' => function($query) {
                                        $query->with('role');
                                    }])->whereDate('expires_at', '<', now())->get();

        // Loop through subscriptions
        foreach ($subscriptions as $subscription) {

            // Dont move admins and owners ads to archive
            // And keep them active forever
            if ($subscription->user->role->group != 'owner' || $subscription->user->role->group != 'administrator') {
            
                $subscription->update([
                    'isExpired' => true,
                    'isExpired' => true,
                ]);

                // Disable user shops
                $shops = Store::whereUserId($subscription->user_id)->get();

                // Loop through shops 
                foreach ($shops as $shop) {
                    $shop->update([
                        'isActive'  => false,
                        'isExpired' => true
                    ]);

                    // Send notification to user
                    $shop->owner->notify(new ShopExpired($shop));
                }

            }

        }
    }
}
