<?php

namespace App\Mail\Administrator\MendelMan;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactSupport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Get request
     * @var [type]
     */
    public $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Set subject
        $subject = $this->request->priority == 'extra' ? __('trans.t_urgent_support_required', [], config('app.locale')) : __('trans.t_support_required', [], config('app.locale'));

        return $this->subject($subject)
                    ->view('mail.administrator.support.contact', compact($this->request));
    }
}
