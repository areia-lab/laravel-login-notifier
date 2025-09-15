<?php

namespace AreiaLab\LoginNotifier\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use AreiaLab\LoginNotifier\Models\LoginHistory;

class UserLoginAlert extends Notification
{
    use Queueable;

    protected LoginHistory $login;
    protected $secureRoute;

    public function __construct(LoginHistory $login)
    {
        $this->login = $login;
        $this->secureRoute = config('login-notifier.secure_url', 'forgot-password');
    }

    /**
     * Notification delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $loginTime = $this->login->created_at->format('F j, Y, g:i a');

        return (new MailMessage)
            ->subject('⚠️ New Login to Your Account')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We detected a new login to your account. If this was you, no action is required.')
            ->line('Here are the login details:')
            ->line("• **Date & Time:** $loginTime")
            ->line("• **IP Address:** {$this->login->ip_address}")
            ->line("• **Device:** {$this->login->device}")
            ->line("• **Browser:** {$this->login->browser}")
            ->line("• **Location:** " . ($this->login->geo_location ?? 'Unknown'))
            ->action('Secure Your Account', url($this->secureRoute))
            ->line('If you did not perform this login, we strongly recommend changing your password immediately and enabling two-factor authentication.');
    }
}
