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
        $customerId= 4213717333;
        return $campaignService->list($customerId);
    }

    public function store(CampaignService $campaignService)
    {
        $customerId= 4213717333;
        return $campaignService->store($customerId);
    }

    public function update(CampaignService $campaignService)
    {
        $customerId= 4213717333;
        $campaignId = 20316601567;
        return $campaignService->update($customerId, $campaignId);
    }

    public function delete(CampaignService $campaignService)
    {
        $customerId= 4213717333;
        $campaignId = 20316601567;
        return $campaignService->delete($customerId, $campaignId);
    }
}
