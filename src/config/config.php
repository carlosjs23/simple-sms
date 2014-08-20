<?php

/*
	|--------------------------------------------------------------------------
	| Simple SMS
	|--------------------------------------------------------------------------
	| Driver
  |   Email:  The Email driver uses the Illuminate\Mail\Mailer instance to
  |           send SMS messages based on the carriers e-mail to SMS gateways.
  |           The Email driver will send messages out based on your Laravel
  |           mail settings.
  |   Twilio: Twilio is an SMS service that sends messages at a affordable
  |           and reliable rate.
  |--------------------------------------------------------------------------
  | From
  |   Email:  The from address must be a valid email address.
  |   Twilio: The from address must be a verified phone number within Twilio.
  |--------------------------------------------------------------------------
  | Twilio Additional Settings
  |   Account SID:  The Account SID associated with your Twilio account.
  |   Auth Token:   The Auth Token associated with your Twilio account.
	|
	*/

return [
  'driver' => 'Selected Driver',
  'from' => 'Your Number or Email',
  'twilio' => [
    'account_sid' => 'Your SID',
    'auth_token' => 'Your Token'
  ]
];