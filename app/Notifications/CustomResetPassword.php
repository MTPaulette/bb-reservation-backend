<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
// use Illuminate\Auth\Notifications\ResetPassword;

// class CustomResetPassword extends ResetPassword implements ShouldQueue
class CustomResetPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public string $token
    ){}

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
        $language = $notifiable->language ? $notifiable->language : 'fr';
        $link = env('APP_FRONTEND_URL')."/{$language}/auth/reset-password?token={$this->token}";

        if($language == 'en') {
            return
            (new MailMessage)
            ->subject('Password Reset')
            ->markdown('notifications.en.reset-password', [
                'notifiable' => $notifiable,
                'link' => $link
            ]);
        }

        return
        (new MailMessage)
        ->subject('Réinitialisation de mot de passe')
        ->markdown('notifications.fr.reset-password', [
            'notifiable' => $notifiable,
            'link' => $link
        ]);
    }

    public function toMaill($notifiable) {
        $language = $notifiable->language;
        $link = env('APP_FRONTEND_URL')."/{$language}/auth/reset-password?token={$this->token}";

        return (new MailMessage)
            ->subject('Reset Password')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', url($link))
            // ->action('Reset Password', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('If you did not request a password reset, no further action is required.');
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
