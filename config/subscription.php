<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Subscription Email Reminders
    |--------------------------------------------------------------------------
    |
    | Enable or disable automatic email reminders for subscription expirations.
    | When enabled, the system will send reminder emails to members before
    | their subscription expires and shortly after expiration.
    |
    */
    'email_reminders_enabled' => env('SUBSCRIPTION_EMAIL_REMINDERS_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Reminder Days Before Expiration
    |--------------------------------------------------------------------------
    |
    | Number of days before expiration to send reminder emails.
    | The system will send reminders on these specific days before expiry.
    | Default: 7 days, 3 days, and 1 day before expiration.
    |
    */
    'reminder_days_before' => [7, 3, 1],

    /*
    |--------------------------------------------------------------------------
    | Reminder Days After Expiration
    |--------------------------------------------------------------------------
    |
    | Number of days after expiration to send reminder emails.
    | The system will send reminders on these specific days after expiry.
    | Default: 1 day, 3 days, and 7 days after expiration.
    |
    */
    'reminder_days_after' => [1, 3, 7],

    /*
    |--------------------------------------------------------------------------
    | Email Queue
    |--------------------------------------------------------------------------
    |
    | Whether to queue subscription reminder emails for better performance.
    | When enabled, emails will be processed asynchronously.
    |
    */
    'queue_emails' => env('SUBSCRIPTION_QUEUE_EMAILS', true),

    /*
    |--------------------------------------------------------------------------
    | Email Queue Name
    |--------------------------------------------------------------------------
    |
    | The name of the queue to use for subscription reminder emails.
    | Only used when 'queue_emails' is enabled.
    |
    */
    'queue_name' => env('SUBSCRIPTION_QUEUE_NAME', 'subscription-reminders'),

    /*
    |--------------------------------------------------------------------------
    | Default Subscription Type
    |--------------------------------------------------------------------------
    |
    | The default subscription type to use when creating new subscriptions
    | if no specific type is provided.
    |
    */
    'default_type_id' => env('SUBSCRIPTION_DEFAULT_TYPE_ID'),

    /*
    |--------------------------------------------------------------------------
    | Grace Period
    |--------------------------------------------------------------------------
    |
    | Number of days after expiration before a member is considered inactive.
    | This allows for a grace period for renewal.
    |
    */
    'grace_period_days' => env('SUBSCRIPTION_GRACE_PERIOD_DAYS', 7),
];
