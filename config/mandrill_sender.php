<?php
if (!defined('MANDRILL_APIKEY')) {
    define('MANDRILL_APIKEY', env('MANDRILL_APIKEY'));
}

return [
    'default_subject' => '',
    'api_key' => env('MANDRILL_APIKEY'),
    'log_path' => storage_path('logs/mandrillSender.log'),
    'debug' => true,
    'templates' => [

    ]
];
