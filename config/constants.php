<?php

return [
    'default_error_message' => 'An error occurred, Please try again',

    'default_card_amount' => 50,

    'payment_gateway' => [

        'paystack' => [
            'secret_key' => env('PAYSTACK_SECRET_KEY', ''),
            'public_key' => env('PAYSTACK_PUBLIC_KEY', '')
        ]
    ],
    'notification' => [
        'tmnotify' => [
            'url' => [
                'email' => env('TMNOTIFY_EMAIL_URL', 'https://services-staging.tm30.net/notifications/v1/email'),
                'sms' => env('TMNOTIFY_SMS_URL', 'https://services-staging.tm30.net/alerts/v1/sms')
            ],
            'mail' => [
                'from'  => env('TMNOTIFY_MAIL_FROM', 'no-reply@adashi.com')
            ],
            'sms' => [
                'from' => env('TMNOTIFY_SMS_FROM', 'Adashi')
            ],
            'client_id' => env('TMNOTIFY_CLIENT_ID', ''),
            'secret_key' => env('TMNOTIFY_CLIENT_SECRET'),
            'name' => env('TMNOTIFY_CLIENT_NAME')
        ]
    ],
    'date_default' => [
        'day_of_month' => '',
        'day_of_week' => '',
        'hour_of_day' => ''
    ]

];
