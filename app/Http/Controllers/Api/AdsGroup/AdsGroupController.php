<?php

namespace App\Http\Controllers\Api\AdsGroup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AdsGroups\AdsGroupService;

class AdsGroupController extends Controller
{
    public function index(AdsGroupService $adsGroupService)
    {
        $customerId= config('google_ads.customerId');
        return $adsGroupService->list($customerId);
    }

    public function store(AdsGroupService $adsGroupService, Request $request)
    {
        $paramData = $request->all();
        $customerId= config('google_ads.customerId');
        return $adsGroupService->store($customerId, $paramData);
    }

    public function delete($adGroupId, AdsGroupService $adsGroupService)
    {
        $customerId= config('google_ads.customerId');
        return $adsGroupService->delete($customerId, $adGroupId);
    }
}
