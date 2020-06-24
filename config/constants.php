<?php

return [
    'default_error_message' => 'An error occurred, Please try again',

    'default_card_amount' => 50,
    
    'payment_gateway' => [

        'paystack' => [
            'secret_key' => env('PAYSTACK_SECRET_KEY', 'sd'),
            'public_key' => env('PAYSTACK_PUBLIC_KEY', 'pbmmm')
        ]
    ]
];