<?php

namespace App\Services\AdsGroup;

use DateTime;
use GetOpt\GetOpt;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentNames;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentParser;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsException;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\V14\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\V14\Services\GoogleAdsRow;
use App\Classes\GoogleAdsOauth;
use Google\Ads\GoogleAds\V14\Resources\AdGroup;
use Google\Ads\GoogleAds\V14\Services\AdGroupOperation;
use Google\ApiCore\ApiException;
use Google\Ads\GoogleAds\Util\V14\ResourceNames;
use Google\Ads\GoogleAds\V14\Enums\AdGroupStatusEnum\AdGroupStatus;
use Google\Ads\GoogleAds\V14\Enums\AdGroupTypeEnum\AdGroupType;
use App\Services\BaseService;
use Illuminate\Support\Facades\Response;
use Exception;

class AdsGroupService extends BaseService
{
    private GoogleAdsClient $googleAdsClient;

    private const PAGE_SIZE = 1000;

    public function __construct(GoogleAdsClient $googleAdsClient)
    {
        $this->googleAdsClient = $googleAdsClient;
    }

    public function list($customerId, $campaignId = null)
    {
        try {
            $googleAdsServiceClient = $this->googleAdsClient->getGoogleAdsServiceClient();
            // Creates a query that retrieves all ad groups.
            $query = 'SELECT campaign.id, ad_group.id, ad_group.name FROM ad_group';
            if ($campaignId !== null) {
                $query .= " WHERE campaign.id = $campaignId";
            }

            // Issues a search request by specifying page size.
            $response =
                $googleAdsServiceClient->search($customerId, $query, ['pageSize' => self::PAGE_SIZE]);

            // Iterates over all rows in all pages and prints the requested field values for
            // the ad group in each row.
            $data = [];
            foreach ($response->iterateAllElements() as $googleAdsRow) {
                $data[] = [
                    'campaign_id' => $googleAdsRow->getCampaign()->getId(),
                    'ads_group_id' => $googleAdsRow->getAdGroup()->getId(),
                    'ads_group_name' => $googleAdsRow->getAdGroup()->getName()
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

    public function store($customerId, $campaignId)
    {
        try {
            $campaignResourceName = ResourceNames::forCampaign($customerId, $campaignId);

            $operations = [];

            // Constructs an ad group and sets an optional CPC value.
            $adGroup1 = new AdGroup([
                'name' => 'Earth to Mars Cruises #' . (new DateTime())->format("Y-m-d\TH:i:s.vP"),
                'campaign' => $campaignResourceName,
                'status' => AdGroupStatus::ENABLED,
                'type' => AdGroupType::SEARCH_STANDARD,
                'cpc_bid_micros' => 10000000
            ]);

            $adGroupOperation1 = new AdGroupOperation();
            $adGroupOperation1->setCreate($adGroup1);
            $operations[] = $adGroupOperation1;

            // Issues a mutate request to add the ad groups.
            $adGroupServiceClient = $this->googleAdsClient->getAdGroupServiceClient();
            $response = $adGroupServiceClient->mutateAdGroups(
                $customerId,
                $operations
            );
            
            return Response::json([
                'success' => true,
                'message' => 'Added ad groups',
            ]);
        } catch (Exception $e) {
            $this->handleGoogleAdsExeption($e);
        }
    }
}
