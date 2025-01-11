<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReservation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private Reservation $reservation,
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
        $admin_url = env('APP_FRONTEND_URL')."/$language/admin/reservations/".$this->reservation->id;


        if($language == 'en') {
            return
            (new MailMessage)
            ->subject(
                $notifiable->role_id == 2 ?
                'New reservation':
                'New reservation'.$this->reservation->ressource->space->name
            )
            ->markdown('notifications.en.new-reservation', [
                'notifiable' => $notifiable,
                'reservation' => $this->reservation,
                'admin_url' => $admin_url
            ]);
        }

        return
        (new MailMessage)
        ->subject(
            $notifiable->role_id == 2 ?
            'Nouvelle réservation' :
            'Nouvelle réservation '.$this->reservation->ressource->space->name
        )->markdown('notifications.fr.new-reservation', [
            'notifiable' => $notifiable,
            'reservation' => $this->reservation,
            'admin_url' => $admin_url
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
