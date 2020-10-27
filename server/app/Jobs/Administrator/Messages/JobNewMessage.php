<?php

namespace App\Jobs\Administrator\Messages;

use App\Model\User;
use App\Model\AdminMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Administrator\Messages\NewMessage;

class JobNewMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(AdminMessage $message)
    {
        $this->message = $message;
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

        // Get admins that can access messages
        $admins = User::with(['role' => function($q){
                            $q->where('group', '=', 'administrator')
                              ->whereJsonContains('permissions', ['access_admin_messages' => true]);
                        }])->get();

        // Send them notification via email to admins
        foreach ($admins as $admin) {
            $admin->notify(new NewMessage($this->message));
        }

        // Send them notification via email to admins
        foreach ($owners as $owner) {
            $owner->notify(new NewMessage($this->message));
        }
    }
}
