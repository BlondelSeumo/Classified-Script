<?php

namespace App\Jobs\Main\Comments;

use App\Model\Comment;
use App\Model\Classified;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Main\Comments\NewComment;

class JobNewComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $comment;

    protected $deal;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment, Classified $deal)
    {
        $this->comment = $comment;
        $this->deal    = $deal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->deal->user->notify(new NewComment($this->comment, $this->deal));
    }
}
