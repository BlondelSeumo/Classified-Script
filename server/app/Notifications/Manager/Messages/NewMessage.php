<?php

namespace App\Notifications\Manager\Messages;

use App\Model\Store;
use App\Model\StoreMessage;
use Illuminate\Bus\Queueable;
use App\Model\SettingsGeneral;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewMessage extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    protected $shop;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(StoreMessage $message, Store $shop)
    {
        $this->message = $message;
        $this->shop    = $shop;
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
            'message' => $this->message,
            'shop'    => $this->shop,
            'logo'    => $logo
        );

        // Send the notification        
        return (new MailMessage)
                ->subject("['" . config('app.name') . "'] " . __('trans.t_shop_new_message', [], config('app.locale')) . " " . $this->shop->store)
                ->markdown('mail.manager.messages.message', $data);
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
