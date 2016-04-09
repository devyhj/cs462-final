<?php
return [
    'twilio' => [
        'default' => 'twilio',
        'connections' => [
            'twilio' => [
                /*
                |--------------------------------------------------------------------------
                | SID
                |--------------------------------------------------------------------------
                |
                | Your Twilio Account SID #
                |
                */
                'sid' => getenv('TWILIO_SID') ?: 'AC5bcc38dd8abf71d705d6753d57a4c3b4',
                /*
                |--------------------------------------------------------------------------
                | Access Token
                |--------------------------------------------------------------------------
                |
                | Access token that can be found in your Twilio dashboard
                |
                */
                'token' => getenv('TWILIO_TOKEN') ?: '15e87a1bda86c89be07ede5d2f77418f',
                /*
                |--------------------------------------------------------------------------
                | From Number
                |--------------------------------------------------------------------------
                |
                | The Phone number registered with Twilio that your SMS & Calls will come from
                |
                */
                'from' => getenv('TWILIO_FROM') ?: '+14694163451',
                /*
                |--------------------------------------------------------------------------
                | Verify Twilio's SSL Certificates
                |--------------------------------------------------------------------------
                |
                | Allows the client to bypass verifying Twilio's SSL certificates.
                | It is STRONGLY advised to leave this set to true for production environments.
                |
                */
                'ssl_verify' => true,
            ],
        ],
    ],
];