<?php

namespace App\Http\Controllers\Api\AdsGroup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AdsGroups\AdsGroupService;

class AdsGroupController extends Controller
{
    public function index(AdsGroupService $adsGroupService)
    {
        $customerId = 4213717333;
        $campaignId = null; // optional
        // $campaignId = 20311922291;
        return $adsGroupService->list($customerId, $campaignId);
    }

    public function store(AdsGroupService $adsGroupService, Request $request)
    {
        $paramData = $request->all();
        return $adsGroupService->store($paramData);
    }

    public function delete($adGroupId, AdsGroupService $adsGroupService)
    {
        $customerId = 4213717333;
        return $adsGroupService->delete($customerId, $adGroupId);
    }
}
