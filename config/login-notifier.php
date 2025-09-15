<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notify User
    |--------------------------------------------------------------------------
    | Set to true to send an email notification to the user when a login
    | is detected from a new device or IP address.
    */
    'notify_user' => env('LOGIN_NOTIFIER_NOTIFY_USER', true),

    /*
    |--------------------------------------------------------------------------
    | Notify Admin
    |--------------------------------------------------------------------------
    | Set to true to send an email notification to the admin for every login.
    */
    'notify_admin' => env('LOGIN_NOTIFIER_NOTIFY_ADMIN', true),

    /*
    |--------------------------------------------------------------------------
    | Admin Email
    |--------------------------------------------------------------------------
    | Email address to receive admin notifications.
    | You can set it in your .env file as LOGIN_NOTIFIER_ADMIN_EMAIL
    */
    'admin_email' => env('LOGIN_NOTIFIER_ADMIN_EMAIL', 'admin@example.com'),

    /*
    |--------------------------------------------------------------------------
    | Account Security URL
    |--------------------------------------------------------------------------
    | URL for the user to secure their account if a suspicious login is detected.
    | This should point to your password reset or account security page.
    */
    'secure_url' => env('LOGIN_NOTIFIER_SECURE_URL', 'forgot-password'),

    /*
    |--------------------------------------------------------------------------
    | Optional: Geo-location service
    |--------------------------------------------------------------------------
    | If you want to use a different geo-location service or provider, you can
    | configure it here. Default uses Stevebauman\Location.
    */
    'geo_provider' => env('LOGIN_NOTIFIER_GEO_PROVIDER', 'default'),

];
