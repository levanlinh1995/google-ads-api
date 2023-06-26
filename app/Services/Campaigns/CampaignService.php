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
            $query = 'SELECT campaign.id, campaign.name FROM campaign ORDER BY campaign.id';

            // Issues a search stream request.
            $stream = $googleAdsServiceClient->search($customerId, $query, ['pageSize' => self::PAGE_SIZE]);

            $data = [];
            foreach ($stream->iterateAllElements() as $googleAdsRow) {
                $data[] = [
                    'id' => $googleAdsRow->getCampaign()->getId(),
                    'name' => $googleAdsRow->getCampaign()->getName()
                ];
            }

            return Response::json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (Exception $e) {
            $this->handleGoogleAdsExeption($e);
        }
    }

    public function store($customerId)
    {
        try {
            // Creates a single shared budget to be used by the campaigns added below.
            $budgetResourceName = self::addCampaignBudget($this->googleAdsClient, $customerId);

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
                'name' => 'Interplanetary Cruise #' . (new DateTime())->format("Y-m-d\TH:i:s.vP"),
                'advertising_channel_type' => AdvertisingChannelType::SEARCH,
                // Recommendation: Set the campaign to PAUSED when creating it to prevent
                // the ads from immediately serving. Set to ENABLED once you've added
                // targeting and the ads are ready to serve.
                'status' => CampaignStatus::PAUSED,
                // Sets the bidding strategy and budget.
                'manual_cpc' => new ManualCpc(),
                'campaign_budget' => $budgetResourceName,
                // Adds the network settings configured above.
                'network_settings' => $networkSettings,
                // Optional: Sets the start and end dates.
                'start_date' => date('Ymd', strtotime('+1 day')),
                'end_date' => date('Ymd', strtotime('+1 month'))
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
            $this->handleGoogleAdsExeption($e);
        }
    }

    public function update($customerId, $campaignId)
    {
        try {
            // Creates a campaign object with the specified resource name and other changes.
            $campaign = new Campaign([
                'resource_name' => ResourceNames::forCampaign($customerId, $campaignId),
                'status' => CampaignStatus::ENABLED
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

            $updatedCampaign = $response->getResults()[0];
            return Response::json([
                'success' => true,
                'message' => "Updated campaign with resource name: " . $updatedCampaign->getResourceName(),
            ]);
        } catch (Exception $e) {
            $this->handleGoogleAdsExeption($e);
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
            $this->handleGoogleAdsExeption($e);
        }
    }

    private static function addCampaignBudget($googleAdsClient, $customerId)
    {
        // Creates a campaign budget.
        $budget = new CampaignBudget([
            'name' => 'Interplanetary Cruise Budget #' . (new DateTime())->format("Y-m-d\TH:i:s.vP"),
            'delivery_method' => BudgetDeliveryMethod::STANDARD,
            'amount_micros' => 1000000
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
        printf("Added budget named '%s'%s", $addedBudget->getResourceName(), PHP_EOL);

        return $addedBudget->getResourceName();
    }
}
