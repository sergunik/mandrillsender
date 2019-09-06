<?php
if (!defined('MANDRILL_APIKEY')) {
    define('MANDRILL_APIKEY', env('MANDRILL_APIKEY'));
}

return [
    'api_key' => env('MANDRILL_APIKEY'),
    'log_path' => storage_path('logs/mandrillSender.log'),
    'templates' => [

    ]
];
