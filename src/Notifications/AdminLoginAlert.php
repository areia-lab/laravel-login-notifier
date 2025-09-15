<?php

namespace AreiaLab\LoginNotifier\Notifications;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use AreiaLab\LoginNotifier\Models\LoginHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Auth\User;

class AdminLoginAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $login;

    public function __construct(User $user, LoginHistory $login)
    {
        $this->user = $user;
        $this->login = $login;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('User Logged in from New Device/IP')
            ->line("User {$this->user->name} logged in.")
            ->line('IP: ' . $this->login->ip_address)
            ->line('Device: ' . $this->login->device)
            ->line('Browser: ' . $this->login->browser)
            ->line('Location: ' . $this->login->geo_location);
    }
}
