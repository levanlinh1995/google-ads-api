<?php

namespace App\Http\Controllers\Api\Ads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ads\AdsService;

class AdsController
{
    public function index(AdsService $adsService)
    {
        $customerId = 4213717333;
        $adGroupId = 150503835053;
        return $adsService->list($customerId, $adGroupId);
    }

    public function store(AdsService $adsService)
    {
        $customerId = 4213717333;
        $adGroupId = 150503835053;
        return $adsService->store($customerId, $adGroupId);
        
    }
    
    public function update(AdsService $adsService)
    {
        $adId = 663709731813;
        $customerId = 4213717333;
        return $adsService->update($customerId, $adId);
    }

    public function delete(AdsService $adsService)
    {
        $adGroupId = 150503835053;
        $adId = 663710530689;
        $customerId = 4213717333;
        return $adsService->delete($customerId, $adGroupId, $adId);
    }
}
