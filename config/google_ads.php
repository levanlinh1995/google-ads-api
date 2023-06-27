<?php
return [
    'developerToken' => env('GOOGLE_ADS_DEVELOPER_TOKEN', ''),
    'clientCustomerId' => env('GOOGLE_ADS_CLIENT_CUSTOMER_ID', ''),
    'clientId' => env('GOOGLE_CLIENT_ID', ''),
    'clientSecret' => env('GOOGLE_CLIENT_SECRET', ''),
    'refreshToken' => env('GOOGLE_ADS_REFRESH_TOKEN', ''),
    'authorizationUri' => env('GOOGLE_ADS_AUTHORIZATION_URL', ''),
    'redirectUri' => env('GOOGLE_ADS_REDIRECT_URL', ''),
    'scope' => env('GOOGLE_ADS_REDIRECT_SCOPE', ''),

    // just for test (this is account for CRUD campaign, ad groups, ads)
    'customerId' => env('GOOGLE_ADS_CUSTOMER_ID', ''),
];