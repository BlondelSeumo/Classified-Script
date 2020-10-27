<?php

namespace App\Mail\Administrator\Themes;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Model\SettingsGeneral;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestTheme extends Mailable
{
    use Queueable, SerializesModels;

    protected $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
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
        // Get site logo
        $settings = SettingsGeneral::find(1);

        // Get data to sent
        $data = array(
            'request' => $this->request, 
            'logo'    => $settings->whiteLogo ? index('storage/app/' . $settings->whiteLogo) : index('storage/app/uploads/default/settings/logo/white.png')
        );

        return $this->subject(__('trans.t_theme_requested', [], config('app.locale')))
                    ->view('mail.administrator.themes.request', $data);
    }
}
