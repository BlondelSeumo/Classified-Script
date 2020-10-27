<?php

namespace App\Jobs\Administrator\Maintenance;

use App\Model\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Administrator\Maintenance\MaintenanceEnabled;
use App\Notifications\Administrator\Maintenance\MaintenanceDisabled;

class JobMaintenance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $username;

    protected $enabled;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($enabled, $username)
    {
        $this->enabled  = $enabled;
        $this->username = $username;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get main owner
        $admin    = User::find(1);

        // Check if maintenance enabled
        if ($this->enabled) {

            // Send the notification
            $admin->notify(new MaintenanceEnabled($this->username));

        }else{

            $admin->notify(new MaintenanceDisabled($this->username));

        }
    }
}
