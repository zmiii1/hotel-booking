<?php

return [
    'secret_key' => env('XENDIT_SECRET_KEY', ''),
    'public_key' => env('XENDIT_PUBLIC_KEY', ''),
    'callback_token' => env('XENDIT_CALLBACK_TOKEN', ''),
    'test_mode' => env('XENDIT_TEST_MODE', true),
    'dev_mode' => env('XENDIT_DEV_MODE', env('APP_ENV') === 'local'),
];