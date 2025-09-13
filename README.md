# Laravel Login Notifier

**Package:** `areia-lab/laravel-login-notifier`

Notify users when they log in from a **new device or IP**, log location info, and notify admin.

## Installation

```bash
composer require areia-lab/laravel-login-notifier
php artisan vendor:publish --tag=login-notifier
php artisan migrate
```

## Configuration

Edit `config/login-notifier.php`:

```php
return [
    'admin_email' => 'admin@example.com',
    'notify_user' => true,
    'notify_admin' => true,
];
```

## Features

- Detect new device/IP logins.
- Send email notifications to the user.
- Send alerts to admin.
- Log device, browser, platform, IP, and geo-location.
- Fully configurable via config file.

## Usage

Just install and migrate. On every login, the package automatically triggers notifications.

```php
use Illuminate\Support\Facades\Auth;

Auth::attempt(['email' => 'user@example.com', 'password' => 'password']);
```

Notifications and logging will happen automatically.
