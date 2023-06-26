<?php

namespace App\Services;

use Exception;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsException;
use Google\ApiCore\ApiException;
use Illuminate\Support\Facades\Response;

class BaseService
{
    protected function handleGoogleAdsExeption(Exception $e)
    {
        if ($e instanceof GoogleAdsException) {
            $message = "Request with ID " . $e->getRequestId() . "has failed. Google Ads failure details";
        } else if ($e instanceof ApiException) {
            $message = "ApiException was thrown with message " . $e->getMessage();
        } else {
            $message = $e->getMessage();
        }

        throw new \Exception($message);
    }
}