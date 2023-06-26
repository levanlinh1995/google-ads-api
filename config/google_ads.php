<?php
return [
    'developerToken' => env('GOOGLE_ADS_DEVELOPER_TOKEN', ''),
    'clientCustomerId' => "CLIENT-CUSTOMER-ID",
    'clientId' => env('GOOGLE_CLIENT_ID', ''),
    'clientSecret' => env('GOOGLE_CLIENT_SECRET', ''),
    'refreshToken' => env('GOOGLE_ADS_REFRESH_TOKEN', ''),
    'authorizationUri' => env('GOOGLE_ADS_AUTHORIZATION_URL', ''),
    'redirectUri' => env('GOOGLE_ADS_REDIRECT_URL', ''),
    'scope' => env('GOOGLE_ADS_REDIRECT_SCOPE', '')
];