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

    protected User $user;
    protected LoginHistory $login;

    public function __construct(User $user, LoginHistory $login)
    {
        $this->user = $user;
        $this->login = $login;
    }

    /**
     * Notification channels.
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
            ->subject("⚠️ New Login Alert: {$this->user->name}")
            ->greeting("Hello Admin,")
            ->line("User **{$this->user->name}** has logged in from a new device or IP.")
            ->line('Login Details:')
            ->line("• **Date & Time:** $loginTime")
            ->line("• **IP Address:** {$this->login->ip_address}")
            ->line("• **Device:** {$this->login->device}")
            ->line("• **Browser:** {$this->login->browser}")
            ->line("• **Location:** " . ($this->login->geo_location ?? 'Unknown'))
            ->action('View User Activity', url('/admin/users/' . $this->user->id . '/logins'))
            ->line('Please review this activity to ensure account security.');
    }
}
