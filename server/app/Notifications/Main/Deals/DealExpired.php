<?php

namespace App\Notifications\Main\Deals;

use App\Model\Classified;
use Illuminate\Bus\Queueable;
use App\Model\SettingsGeneral;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DealExpired extends Notification
{
    use Queueable;

    protected $deal;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Classified $deal)
    {
        $this->deal = $deal;
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

        // Get data to sent
        $data     = array(
            'deal'    => $this->deal,
            'logo'    => $logo
        );

        // Send the notification        
        return (new MailMessage)
                ->subject("['" . config('app.name') . "'] " . __('trans.t_your_deal_expired', [], config('app.locale')))
                ->markdown('mail.main.deals.expired', $data);
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
