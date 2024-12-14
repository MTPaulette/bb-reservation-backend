<?php

namespace App\Notifications;

use App\Models\Coupon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CouponExpired extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private Coupon $coupon,
    ) {}

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
        $language = $notifiable->language;
        if($language == 'en') {
            return
            (new MailMessage)
            ->subject('Discount coupon expired')
            ->markdown('notifications.en.coupon-expired', [
                'notifiable' => $notifiable,
                'coupon' => $this->coupon
            ]);
        }

        return
        (new MailMessage)
        ->subject('Coupon de réduction expiré')
        ->markdown('notifications.fr.coupon-expired', [
            'notifiable' => $notifiable,
            'coupon' => $this->coupon
        ]);
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
