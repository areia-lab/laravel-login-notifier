# Laravel Login Notifier 🔔

A Laravel package that **notifies users when they log in from a new device or IP address**.  
Helps improve account security with minimal setup.

---

## 🚀 Installation

```bash
composer require areia-lab/laravel-login-notifier
```

Publish & run migrations:

```bash
php artisan migrate
```

---

## ⚡ Usage

Nothing special to configure — it just works! 🎉

Whenever a user logs in from a **new device/IP**, they will receive an **email notification**.

---

## 🔧 Customization

- Edit the notification class:  
  `src/Notifications/NewDeviceLoginNotification.php`  
  to change email text or add more channels (SMS, Slack, etc.).

- You can also add extra logic (e.g. log geo-location, send alerts to admin).

---

## 📜 License

MIT
