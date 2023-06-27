<?php

namespace App\Http\Controllers\Api\Ads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Ads\AdsService;

class AdsController
{
    public function index(AdsService $adsService)
    {
        $customerId= config('google_ads.customerId');
        return $adsService->list($customerId);
    }

    public function detail($adId, AdsService $adsService)
    {
        $customerId= config('google_ads.customerId');
        return $adsService->detail($customerId, $adId);
    }

    public function store(AdsService $adsService, Request $request)
    {
        $customerId= config('google_ads.customerId');
        $paramData = $request->all();
        return $adsService->store($customerId, $paramData);
    }
    
    public function update($adId, AdsService $adsService, Request $request)
    {
        $paramData = $request->all();
        $customerId= config('google_ads.customerId');
        return $adsService->update($customerId, $adId, $paramData);
    }

    public function delete($adGroupId, $adId, AdsService $adsService)
    {
        $customerId= config('google_ads.customerId');
        return $adsService->delete($customerId, $adGroupId, $adId);
    }
}
