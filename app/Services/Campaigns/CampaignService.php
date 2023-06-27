<?php

namespace App\Services\Campaigns;

use Google\Ads\GoogleAds\V14\Common\ManualCpc;
use Google\Ads\GoogleAds\V14\Enums\AdvertisingChannelTypeEnum\AdvertisingChannelType;
use Google\Ads\GoogleAds\V14\Enums\BudgetDeliveryMethodEnum\BudgetDeliveryMethod;
use Google\Ads\GoogleAds\V14\Enums\CampaignStatusEnum\CampaignStatus;
use Google\Ads\GoogleAds\V14\Resources\Campaign;
use Google\Ads\GoogleAds\V14\Resources\Campaign\NetworkSettings;
use Google\Ads\GoogleAds\V14\Resources\CampaignBudget;
use Google\Ads\GoogleAds\V14\Services\CampaignBudgetOperation;
use Google\Ads\GoogleAds\V14\Services\CampaignOperation;
use DateTime;
use Google\Ads\GoogleAds\Util\V14\ResourceNames;
use Google\Ads\GoogleAds\Util\FieldMasks;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsClient;
use Illuminate\Support\Facades\Response;
use App\Services\BaseService;
use Exception;

class CampaignService extends BaseService
{
    private GoogleAdsClient $googleAdsClient;
    private const PAGE_SIZE = 100;

    public function __construct(GoogleAdsClient $googleAdsClient)
    {
        $this->googleAdsClient = $googleAdsClient;
    }

    public function list($customerId)
    {
        try {
            $googleAdsServiceClient = $this->googleAdsClient->getGoogleAdsServiceClient();

            // Creates a query that retrieves all campaigns.
            $query = "SELECT

                campaign.advertising_channel_type,
                
                campaign.bidding_strategy,
                
                campaign.campaign_budget,
                
                campaign.campaign_group,
                
                campaign.bidding_strategy_type,
                
                campaign.end_date,

                campaign.start_date,
                
                campaign.id,
                
                campaign.name,

                campaign.status,
                
                campaign.optimization_score,

                campaign_budget.name,

                campaign_budget.id,

                campaign_budget.total_amount_micros,

                campaign_budget.type,

                campaign_budget.status,

                campaign_budget.amount_micros,

                campaign_budget.delivery_method,

                campaign_budget.period,

                customer.id,
                
                customer.descriptive_name,
                
                customer.resource_name,
                
                customer.status,
                
                customer.manager,

                customer.currency_code
            
            FROM campaign
            WHERE
                campaign.status IN ('ENABLED', 'PAUSED')
            ORDER BY campaign.id";

            // Issues a search stream request.
            $stream = $googleAdsServiceClient->search($customerId, $query, ['pageSize' => self::PAGE_SIZE]);

            $data = [];
            foreach ($stream->iterateAllElements() as $googleAdsRow) {
                $data[] = [
                    'id' => $googleAdsRow->getCampaign()->getId(),
                    'name' => $googleAdsRow->getCampaign()->getName(),
                    'status' => $googleAdsRow->getCampaign()->getStatus(),
                    'status_name' => CampaignStatus::name($googleAdsRow->getCampaign()->getStatus()),
                    'campaign_budget' => $googleAdsRow->getCampaign()->getCampaignBudget(),
                    'bidding_strategy' => $googleAdsRow->getCampaign()->getBiddingStrategy(),
                    'advertising_channel_type' => $googleAdsRow->getCampaign()->getAdvertisingChannelType(),
                    'advertising_channel_type_name' => AdvertisingChannelType::name($googleAdsRow->getCampaign()->getAdvertisingChannelType()),
                    'campaign_group' => $googleAdsRow->getCampaign()->getCampaignGroup(),
                    'bidding_strategy_type' => $googleAdsRow->getCampaign()->getBiddingStrategyType(),
                    'optimization_score' => $googleAdsRow->getCampaign()->getOptimizationScore(),
                    'start_date' => $googleAdsRow->getCampaign()->getStartDate(),
                    'end_date' => $googleAdsRow->getCampaign()->getEndDate(),
                    'budget' => $googleAdsRow->getCampaignBudget()->getAmountMicros() / 1000000,
                    'campaign_budget_amount_micros' => $googleAdsRow->getCampaignBudget()->getAmountMicros(),
                    'campaign_budget_id' => $googleAdsRow->getCampaignBudget()->getId(),
                    'customer_id' => $googleAdsRow->getCustomer()->getId(),
                    'customer_descriptive_name' => $googleAdsRow->getCustomer()->getDescriptiveName()
                ];
            }

            return Response::json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return $this->handleGoogleAdsExeption($e);
        }
    }

    public function detail($customerId, $campaignId)
    {
        try {
            $googleAdsServiceClient = $this->googleAdsClient->getGoogleAdsServiceClient();

            // Creates a query that retrieves all campaigns.
            $query = "SELECT

                campaign.advertising_channel_type,
                
                campaign.bidding_strategy,
                
                campaign.campaign_budget,
                
                campaign.campaign_group,
                
                campaign.bidding_strategy_type,
                
                campaign.end_date,

                campaign.start_date,
                
                campaign.id,
                
                campaign.name,

                campaign.status,
                
                campaign.optimization_score,

                campaign_budget.name,

                campaign_budget.id,

                campaign_budget.total_amount_micros,

                campaign_budget.type,

                campaign_budget.status,

                campaign_budget.amount_micros,

                campaign_budget.delivery_method,

                campaign_budget.period,

                customer.id,
                
                customer.descriptive_name,
                
                customer.resource_name,
                
                customer.status,
                
                customer.manager,

                customer.currency_code
            
            FROM campaign
            WHERE campaign.id = $campaignId
            LIMIT 1";

            // Issues a search stream request.
            $stream = $googleAdsServiceClient->search($customerId, $query);

            $data = [];
            foreach ($stream->iterateAllElements() as $googleAdsRow) {
                $data[] = [
                    'id' => $googleAdsRow->getCampaign()->getId(),
                    'name' => $googleAdsRow->getCampaign()->getName(),
                    'status' => $googleAdsRow->getCampaign()->getStatus(),
                    'status_name' => CampaignStatus::name($googleAdsRow->getCampaign()->getStatus()),
                    'campaign_budget' => $googleAdsRow->getCampaign()->getCampaignBudget(),
                    'bidding_strategy' => $googleAdsRow->getCampaign()->getBiddingStrategy(),
                    'advertising_channel_type' => $googleAdsRow->getCampaign()->getAdvertisingChannelType(),
                    'advertising_channel_type_name' => AdvertisingChannelType::name($googleAdsRow->getCampaign()->getAdvertisingChannelType()),
                    'campaign_group' => $googleAdsRow->getCampaign()->getCampaignGroup(),
                    'bidding_strategy_type' => $googleAdsRow->getCampaign()->getBiddingStrategyType(),
                    'optimization_score' => $googleAdsRow->getCampaign()->getOptimizationScore(),
                    'start_date' => $googleAdsRow->getCampaign()->getStartDate(),
                    'end_date' => $googleAdsRow->getCampaign()->getEndDate(),
                    'budget' => $googleAdsRow->getCampaignBudget()->getAmountMicros() / 1000000,
                    'campaign_budget_amount_micros' => $googleAdsRow->getCampaignBudget()->getAmountMicros(),
                    'campaign_budget_id' => $googleAdsRow->getCampaignBudget()->getId(),
                    'customer_id' => $googleAdsRow->getCustomer()->getId(),
                    'customer_descriptive_name' => $googleAdsRow->getCustomer()->getDescriptiveName()
                ];
            }

            return Response::json([
                'success' => true,
                'data' => count($data) > 0 ? $data[0] : [],
            ]);
        } catch (Exception $e) {
            return $this->handleGoogleAdsExeption($e);
        }
    }

    public function store($paramData)
    {
        $customerId = (int) $paramData['customerId'];
        $name = $paramData['name'];
        $startDate = $paramData['start_date'];
        $endDate = $paramData['end_date'];
        $status = (int) $paramData['status'];
        $budget = (int) $paramData['budget'];
        $advertisingChannelType = (int) $paramData['advertisingChannelType'];

        try {
            // Creates a single shared budget to be used by the campaigns added below.
            $budgetResourceName = self::addCampaignBudget($this->googleAdsClient, $customerId, $budget);

            // Configures the campaign network options.
            $networkSettings = new NetworkSettings([
                'target_google_search' => true,
                'target_search_network' => true,
                'target_content_network' => true,
                'target_partner_search_network' => false
            ]);

            $campaignOperations = [];
            // Creates a campaign.
            $campaign = new Campaign([
                'name' => $name,
                'advertising_channel_type' => $advertisingChannelType,
                // Recommendation: Set the campaign to PAUSED when creating it to prevent
                // the ads from immediately serving. Set to ENABLED once you've added
                // targeting and the ads are ready to serve.
                'status' => $status,
                // Sets the bidding strategy and budget.
                'manual_cpc' => new ManualCpc(),
                'campaign_budget' => $budgetResourceName,
                // Adds the network settings configured above.
                'network_settings' => $networkSettings,
                // Optional: Sets the start and end dates.
                'start_date' => date('Ymd', strtotime($startDate)),
                'end_date' => date('Ymd', strtotime($endDate))
            ]);

            // Creates a campaign operation.
            $campaignOperation = new CampaignOperation();
            $campaignOperation->setCreate($campaign);
            $campaignOperations[] = $campaignOperation;

            // Issues a mutate request to add campaigns.
            $campaignServiceClient = $this->googleAdsClient->getCampaignServiceClient();
            $response = $campaignServiceClient->mutateCampaigns($customerId, $campaignOperations);

            return Response::json([
                'success' => true,
                'message' => 'Added campaign',
            ]);
        } catch (Exception $e) {
            return $this->handleGoogleAdsExeption($e);
        }
    }

    public function update($campaignId, $paramData)
    {
        $customerId = (int) $paramData['customerId'];
        $name = $paramData['name'];
        $startDate = $paramData['start_date'];
        $endDate = $paramData['end_date'];
        $status = (int) $paramData['status'];
        $budget = (int) $paramData['budget'];
        // $advertisingChannelType = (int) $paramData['advertisingChannelType'];
        $campaignBudgetId = (int) $paramData['campaignBudgetId'];

        try {
            // Creates a campaign object with the specified resource name and other changes.
            $campaign = new Campaign([
                'resource_name' => ResourceNames::forCampaign($customerId, $campaignId),
                'name' => $name,
                // 'advertising_channel_type' => $advertisingChannelType,
                'status' => $status,
                'start_date' => date('Ymd', strtotime($startDate)),
                'end_date' => date('Ymd', strtotime($endDate))
            ]);

            // Constructs an operation that will update the campaign with the specified resource name,
            // using the FieldMasks utility to derive the update mask. This mask tells the Google Ads
            // API which attributes of the campaign you want to change.
            $campaignOperation = new CampaignOperation();
            $campaignOperation->setUpdate($campaign);
            $campaignOperation->setUpdateMask(FieldMasks::allSetFieldsOf($campaign));

            // Issues a mutate request to update the campaign.
            $campaignServiceClient = $this->googleAdsClient->getCampaignServiceClient();
            $response = $campaignServiceClient->mutateCampaigns(
                $customerId,
                [$campaignOperation]
            );

            // update budget
            $campaignBudget = new CampaignBudget([
                'resource_name' => ResourceNames::forCampaignBudget($customerId, $campaignBudgetId),
                'amount_micros' => $budget * 1000000
            ]);
            $campaignBudgetOperation = new CampaignBudgetOperation();
            $campaignBudgetOperation->setUpdate($campaignBudget);
            $campaignBudgetOperation->setUpdateMask(FieldMasks::allSetFieldsOf($campaignBudget));
            $campaignBudgetServiceClient = $this->googleAdsClient->getCampaignBudgetServiceClient();
            $response = $campaignBudgetServiceClient->mutateCampaignBudgets(
                $customerId,
                [$campaignBudgetOperation]
            );

            $updatedCampaign = $response->getResults()[0];
            return Response::json([
                'success' => true,
                'message' => "Updated campaign with resource name: " . $updatedCampaign->getResourceName(),
            ]);
        } catch (Exception $e) {
            return $this->handleGoogleAdsExeption($e);
        }
    }

    public function delete($customerId, $campaignId)
    {
        try {
            // Creates the resource name of a campaign to remove.
            $campaignResourceName = ResourceNames::forCampaign($customerId, $campaignId);

            // Creates a campaign operation.
            $campaignOperation = new CampaignOperation();
            $campaignOperation->setRemove($campaignResourceName);

            // Issues a mutate request to remove the campaign.
            $campaignServiceClient = $this->googleAdsClient->getCampaignServiceClient();
            $response = $campaignServiceClient->mutateCampaigns($customerId, [$campaignOperation]);

            $removedCampaign = $response->getResults()[0];
            
            return Response::json([
                'success' => true,
                'message' => "Removed campaign with resource name: " . $removedCampaign->getResourceName(),
            ]);
        } catch (Exception $e) {
            return $this->handleGoogleAdsExeption($e);
        }
    }

    private static function addCampaignBudget($googleAdsClient, $customerId, $budget)
    {
        // Creates a campaign budget.
        $budget = new CampaignBudget([
            'name' => 'Budget #' . (new DateTime())->format("Y-m-d\TH:i:s.vP"),
            'delivery_method' => BudgetDeliveryMethod::STANDARD,
            'amount_micros' => $budget * 1000000
        ]);

        // Creates a campaign budget operation.
        $campaignBudgetOperation = new CampaignBudgetOperation();
        $campaignBudgetOperation->setCreate($budget);

        // Issues a mutate request.
        $campaignBudgetServiceClient = $googleAdsClient->getCampaignBudgetServiceClient();
        $response = $campaignBudgetServiceClient->mutateCampaignBudgets(
            $customerId,
            [$campaignBudgetOperation]
        );

        $addedBudget = $response->getResults()[0];

        return $addedBudget->getResourceName();
    }
}
