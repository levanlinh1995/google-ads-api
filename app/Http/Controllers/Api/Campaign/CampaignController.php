<?php

namespace App\Http\Controllers\Api\Campaign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Campaigns\CampaignService;
use Illuminate\Support\Facades\Response;

class CampaignController extends Controller
{
    public function index(CampaignService $campaignService)
    {
        $customerId= config('google_ads.customerId');
        return $campaignService->list($customerId);
    }

    public function detail($campaignId, CampaignService $campaignService)
    {
        $customerId= config('google_ads.customerId');
        return $campaignService->detail($customerId, $campaignId);
    }

    public function store(CampaignService $campaignService, Request $request)
    {
        $paramData = $request->all();
        return $campaignService->store($paramData);
    }

    public function update($campaignId, CampaignService $campaignService, Request $request)
    {
        $paramData = $request->all();
        return $campaignService->update($campaignId, $paramData);
    }

    public function delete($campaignId, CampaignService $campaignService)
    {
        $customerId= config('google_ads.customerId');
        return $campaignService->delete($customerId, (int) $campaignId);
    }
}
