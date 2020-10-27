<?php

namespace App\Notifications\Administrator\Subscriptions;

use App\Model\Subscription;
use Illuminate\Bus\Queueable;
use App\Model\SettingsGeneral;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewSubscription extends Notification
{
    use Queueable;

    protected $subscription;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
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
        $settings  = SettingsGeneral::find(1);

        // Get data to sent
        $data = array(
            'subscription' => $this->subscription,
            'logo'         => $settings->whiteLogo ? index('storage/app/' . $settings->whiteLogo) : index('storage/app/uploads/default/settings/logo/white.png')
        );

        // Send the notification        
        return (new MailMessage)
                ->subject("['" . config('app.name') . "'] " . __('trans.t_you_have_subscription', [], config('app.locale')))
                ->markdown('mail.administrator.subscriptions.subscription', $data);
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
