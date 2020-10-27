<?php

namespace App\Jobs\Administrator\Pending;

use App\Model\User;
use App\Notifications\Administrator\Pending\PendingUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class JobPendingUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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

        // Get admins that can approve users
        $admins = User::with(['role' => function($q){
                            $q->where('group', '=', 'administrator')
                            ->whereJsonContains('permissions', ['approve_users' => true]);
                        }])->get();

        // Send them notification via email to admins
        foreach ($admins as $admin) {
            $admin->notify(new PendingUser($this->user));
        }

        // Send them notification via email to admins
        foreach ($owners as $owner) {
            $owner->notify(new PendingUser($this->user));
        }
    }
}
