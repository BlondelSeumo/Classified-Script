<?php

namespace App\Notifications\Administrator\Deals;

use App\Model\Classified;
use Illuminate\Bus\Queueable;
use App\Model\ClassifiedPayment;
use App\Model\SettingsGeneral;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DealPromoted extends Notification
{
    use Queueable;

    protected $deal;

    protected $payment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Classified $deal, ClassifiedPayment $payment)
    {
        $this->deal    = $deal;
        $this->payment = $payment;
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
            'deal'    => $this->deal, 
            'payment' => $this->payment,
            'logo'    => $settings->whiteLogo ? index('storage/app/' . $settings->whiteLogo) : index('storage/app/uploads/default/settings/logo/white.png')
        );

        // Send the notification        
        return (new MailMessage)
                ->subject("['" . config('app.name') . "'] " . __('trans.t_new_deal_promoted', [], config('app.locale')))
                ->markdown('mail.administrator.deals.promoted', $data);
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
