<?php

namespace App\Http\Controllers\Api\Campaign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsClient;
use Google\Ads\GoogleAds\V14\Common\ManualCpc;
use Google\Ads\GoogleAds\V14\Enums\AdvertisingChannelTypeEnum\AdvertisingChannelType;
use Google\Ads\GoogleAds\V14\Enums\BudgetDeliveryMethodEnum\BudgetDeliveryMethod;
use Google\Ads\GoogleAds\V14\Enums\CampaignStatusEnum\CampaignStatus;
use Google\Ads\GoogleAds\V14\Resources\Campaign;
use Google\Ads\GoogleAds\V14\Resources\Campaign\NetworkSettings;
use Google\Ads\GoogleAds\V14\Resources\CampaignBudget;
use Google\Ads\GoogleAds\V14\Services\CampaignBudgetOperation;
use Google\Ads\GoogleAds\V14\Services\CampaignOperation;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public $googleAdsClient;
    public $customerId;

    public function __construct(GoogleAdsClient $googleAdsClient)
    {
        $this->googleAdsClient = $googleAdsClient;
        $this->customerId = config('google_ads.clientCustomerId');
    }

    public function index()
    {
        $googleAdsServiceClient = $this->googleAdsClient->getGoogleAdsServiceClient();

        // Creates a query that retrieves all campaigns.
        $query = 'SELECT campaign.id, campaign.name FROM campaign ORDER BY campaign.id';

        // Issues a search stream request.
        $stream = $googleAdsServiceClient->searchStream($this->customerId, $query);

        foreach ($stream->iterateAllElements() as $googleAdsRow) {
            printf(
                "Campaign with ID %d and name '%s' was found.%s",
                $googleAdsRow->getCampaign()->getId(),
                $googleAdsRow->getCampaign()->getName(),
                PHP_EOL
            );
        }
    }

    public function store()
    {
        // Creates a single shared budget to be used by the campaigns added below.
        $budgetResourceName = self::addCampaignBudget($this->googleAdsClient);

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
            'name' => 'Interplanetary Cruise #' . Str::random(10),
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
        $response = $campaignServiceClient->mutateCampaigns($this->customerId, $campaignOperations);

        printf("Added %d campaigns:%s", $response->getResults()->count(), PHP_EOL);

        foreach ($response->getResults() as $addedCampaign) {
            print "{$addedCampaign->getResourceName()}" . PHP_EOL;
        }
    }

    private static function addCampaignBudget($googleAdsClient)
    {
        // Creates a campaign budget.
        $budget = new CampaignBudget([
            'name' => 'Interplanetary Cruise Budget #' . Str::random(10),
            'delivery_method' => BudgetDeliveryMethod::STANDARD,
            'amount_micros' => 500000
        ]);

        // Creates a campaign budget operation.
        $campaignBudgetOperation = new CampaignBudgetOperation();
        $campaignBudgetOperation->setCreate($budget);

        // Issues a mutate request.
        $campaignBudgetServiceClient = $googleAdsClient->getCampaignBudgetServiceClient();
        $response = $campaignBudgetServiceClient->mutateCampaignBudgets(
            self::$customerId,
            [$campaignBudgetOperation]
        );

        $addedBudget = $response->getResults()[0];
        printf("Added budget named '%s'%s", $addedBudget->getResourceName(), PHP_EOL);

        return $addedBudget->getResourceName();
    }
}
