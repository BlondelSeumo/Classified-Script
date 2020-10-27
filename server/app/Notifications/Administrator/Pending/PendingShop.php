<?php

namespace App\Notifications\Administrator\Pending;

use App\Model\Store;
use Illuminate\Bus\Queueable;
use App\Model\SettingsGeneral;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PendingShop extends Notification
{
    use Queueable;

    protected $shop;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Store $shop)
    {
        $this->shop = $shop;
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

        // Get data to sent
        $data = array(
            'shop' => $this->shop,
            'logo'     => $settings->whiteLogo ? index('storage/app/' . $settings->whiteLogo) : index('storage/app/uploads/default/settings/logo/white.png')
        );

        // Send the notification        
        return (new MailMessage)
                ->subject("['" . config('app.name') . "'] " . __('trans.t_new_shop_pending', [], config('app.locale')))
                ->markdown('mail.administrator.pending.shop', $data);
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
