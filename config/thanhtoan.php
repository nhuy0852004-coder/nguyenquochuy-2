<?php

return [
    'vnpay' => [
        'tmn_code' => env('VNPAY_TMN_CODE'),
        'hash_secret' => env('VNPAY_HASH_SECRET'),
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNPAY_RETURN_URL', 'http://127.0.0.1:8000/thanh-toan/vnpay/ket-qua'),
    ],

    'payos' => [
        'client_id' => env('PAYOS_CLIENT_ID'),
        'api_key' => env('PAYOS_API_KEY'),
        'checksum_key' => env('PAYOS_CHECKSUM_KEY'),
        'api_url' => env('PAYOS_API_URL', 'https://api-merchant.payos.vn'),
        'return_url' => env('PAYOS_RETURN_URL'),
        'cancel_url' => env('PAYOS_CANCEL_URL'),
        'webhook_url' => env('PAYOS_WEBHOOK_URL'),
    ],
];