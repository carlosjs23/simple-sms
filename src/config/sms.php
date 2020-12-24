<?php

/*
 * https://simplesoftware.io/docs/simple-sms#docs-configuration for more information.
 */
return [
    'driver' => env('SMS_DRIVER', 'labsmobile'),

    'from' => env('SMS_FROM', 'Your Number'),

    'labsmobile' => [
        'client_id' => env('LABSMOBILE_CLIENT_ID', 'Your Labsmobile Client ID'),
        'username'  => env('LABSMOBILE_USERNAME', 'Your Labsmobile Username'),
        'password'  => env('LABSMOBILE_PASSWORD', 'Your Labsmobile Password'),
        'test'      => env('LABSMOBILE_TEST', false),
    ],
];
