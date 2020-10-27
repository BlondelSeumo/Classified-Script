<?php

namespace App\Notifications\Main\Auth;

use Illuminate\Bus\Queueable;
use App\Model\SettingsGeneral;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Get site logo
        $settings = SettingsGeneral::find(1);

        $logo     = $settings->whiteLogo ? index('storage/app/' . $settings->whiteLogo) : index('storage/app/uploads/default/settings/logo/white.png');

        // Send the notification        
        return (new MailMessage)
                ->subject("['" . config('app.name') . "'] " . __('trans.t_your_account_created', [], config('app.locale')))
                ->markdown('mail.main.auth.created', ['logo' => $logo]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
