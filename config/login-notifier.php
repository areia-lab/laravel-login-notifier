<?php

return [
    'notify_user' => true,

    'notify_admin' => true,
    'admin_email' => env('LOGIN_NOTIFIER_ADMIN_EMAIL', 'admin@example.com'),

    'secure_url' => 'forgot-password',
];
