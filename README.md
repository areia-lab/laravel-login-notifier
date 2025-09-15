# Laravel Login Notifier

**Package:** `areia-lab/laravel-login-notifier`

A Laravel package to **monitor user logins**, detect **new devices or IP addresses**, log device and location information, and **notify users and admins** of suspicious or new login activity.

---

## Features

- Detect logins from **new devices or IP addresses**.
- Send **email notifications to users** on new logins.
- Send **alerts to admin** for every login.
- Log **device, browser, platform, IP, and geo-location**.
- Fully **configurable via a config file**.
- Supports **queueable notifications** for production.

---

## Installation

```bash
composer require areia-lab/laravel-login-notifier
```

Publish the configuration and migration files:

```bash
php artisan vendor:publish --tag=login-notifier-config
php artisan vendor:publish --tag=login-notifier-migrations
php artisan migrate
```

> The migrations will create the `login_histories` table to store login data.

---

## Configuration

Edit the published config file `config/login-notifier.php`:

```php
return [
    // Email address to receive admin notifications
    'admin_email' => 'admin@example.com',

    // Notify user on login from new device or IP
    'notify_user' => true,

    // Notify admin on every login
    'notify_admin' => true,
];
```

---

## Usage

This package works **automatically** on every login. You don’t need to call anything manually.

```php
use Illuminate\Support\Facades\Auth;

Auth::attempt(['email' => 'user@example.com', 'password' => 'password']);
```

The following happens automatically:

1. Login is recorded in `login_histories`.
2. New device/IP detection is performed.
3. User notification is sent if enabled.
4. Admin notification is sent if enabled.

---

## Access Login Histories (Facade)

The package provides a **facade** for querying login histories:

```php
use AreiaLab\LoginNotifier\Facades\LoginHistory;

// Get all login histories
$all = LoginHistory::getAllHistories();

// Get recent 10 logins
$recent = LoginHistory::getRecentHistories(10);

// Get all logins for a specific user
$userLogins = LoginHistory::getUserHistories($userId);

// Get last login of a user
$lastLogin = LoginHistory::getLastLoginForUser($userId);

// Get logins from new devices for a user
$newDeviceLogins = LoginHistory::getNewDeviceLoginsForUser($userId);
```

---

## Notifications

### UserLoginAlert

- Sent to users on login from a **new device or IP**.
- Includes: **IP, device, browser, platform, location, timestamp**.
- Includes a **call-to-action button** to secure the account.

### AdminLoginAlert

- Sent to admin on every login.
- Includes user login details: **IP, device, browser, location, timestamp**.
- Provides a **link to review user activity** in the admin dashboard.

---

## Contributing

Contributions, issues, and feature requests are welcome!

1. Fork the repo.
2. Create a feature branch.
3. Submit a Pull Request.

---

## License

MIT License. See [LICENSE](LICENSE) for details.
