<?php

namespace AreiaLab\LoginNotifier\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewDeviceLoginNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $ip;
    protected string $agent;

    public function __construct(string $ip, string $agent)
    {
        $this->ip = $ip;
        $this->agent = $agent;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Login Detected')
            ->line("A new login to your account was detected.")
            ->line("**IP:** {$this->ip}")
            ->line("**Device:** {$this->agent}")
            ->line("If this was not you, please secure your account immediately.");
    }
}
