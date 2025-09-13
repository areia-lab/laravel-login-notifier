<?php

namespace AreiaLab\LoginNotifier\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use AreiaLab\LoginNotifier\Models\LoginHistory;

class UserLoginAlert extends Notification
{
    protected $login;

    public function __construct(LoginHistory $login)
    {
        $this->login = $login;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Login Detected')
            ->line('A new login to your account was detected.')
            ->line('IP: ' . $this->login->ip_address)
            ->line('Device: ' . $this->login->device)
            ->line('Browser: ' . $this->login->browser)
            ->line('Location: ' . $this->login->geo_location)
            ->line('If this was not you, please secure your account immediately.');
    }
}
