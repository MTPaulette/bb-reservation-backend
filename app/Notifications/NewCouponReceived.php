<?php

namespace App\Notifications;

use App\Models\Coupon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCouponReceived extends Notification
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
        $btn_url = env('APP_FRONTEND_URL').'/fr/admin/clients/'.$notifiable->id;
        return
        (new MailMessage)
        ->subject('Nouveau Coupon')
        ->markdown('notifications.new-coupon-received', [
            'name' => $notifiable->lastname,
            'message' => "Vous avez reçu un coupon de réduction ({$this->coupon->name}).
            Il vous donne droit à une réduction de {$this->coupon->percent}% ou {$this->coupon->amount} FCFA 
            sur le montant de la réservation où vous l'utiliseriez.
            Il est utilisable {$this->coupon->total_usage} fois et expire avant le {$this->coupon->expired_on}.
            Vous pouvez utiliser dès présent",
            'action_name' => 'RESERVEZ MAINTENANT',
            'url' => $btn_url
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
