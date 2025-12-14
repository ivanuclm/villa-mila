<?php

return [
    'owner_email' => env('VILLA_OWNER_EMAIL'),
    'owner_name' => env('VILLA_OWNER_NAME', 'Milagros'),
    'owner_phone' => env('VILLA_OWNER_PHONE', '+34 600 000 000'),
    'owner_whatsapp' => env('VILLA_OWNER_WHATSAPP'),

    'check_in_time' => env('VILLA_CHECK_IN_TIME', 'a partir de las 16:00'),
    'check_out_time' => env('VILLA_CHECK_OUT_TIME', 'antes de las 12:00'),

    'payment' => [
        'bank_account' => env('VILLA_BANK_ACCOUNT', 'ES00 0000 0000 0000 0000 0000'),
        'account_name' => env('VILLA_BANK_ACCOUNT_HOLDER', 'Milagros – Villa Mila'),
        'instructions' => env('VILLA_PAYMENT_NOTES', 'Indica en el concepto tu nombre y fechas y envía el comprobante del pago.'),
    ],

    'house_rules_url' => env('VILLA_HOUSE_RULES_URL'),
    'guidebook_url' => env('VILLA_GUIDEBOOK_URL'),
    'contract_url' => env('VILLA_CONTRACT_URL'),
];
