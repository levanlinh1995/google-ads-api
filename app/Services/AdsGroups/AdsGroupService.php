<?php

namespace App\Services\AdsGroups;

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
            $query = "
            SELECT

                ad_group.campaign,
                
                ad_group.cpc_bid_micros,
                
                ad_group.cpm_bid_micros,
                
                ad_group.id,
                
                ad_group.name,
                
                ad_group.status,
                
                ad_group.resource_name,
                
                ad_group.type,
                
                campaign.id,
                
                campaign.name,
                
                campaign.status,
                
                customer.id,
                
                customer.descriptive_name,
                
                customer.resource_name,
                
                customer.status,
                
                customer.manager,

                customer.currency_code
                
            FROM ad_group
            WHERE
                ad_group.status IN ('ENABLED', 'PAUSED')
            ";
            if ($campaignId !== null) {
                $query .= " AND campaign.id = $campaignId";
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
                    'campaign_name' => $googleAdsRow->getCampaign()->getName(),
                    'campaign_status' => $googleAdsRow->getCampaign()->getStatus(),
                    'ads_group_id' => $googleAdsRow->getAdGroup()->getId(),
                    'ads_group_name' => $googleAdsRow->getAdGroup()->getName(),
                    'ads_group_id' => $googleAdsRow->getAdGroup()->getId(),
                    'ads_group_status' => $googleAdsRow->getAdGroup()->getStatus(),
                    'ads_group_status_name' => AdGroupStatus::name($googleAdsRow->getAdGroup()->getStatus()),
                    'customer_status' => $googleAdsRow->getCustomer()->getStatus(),
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

    public function store($paramData)
    {
        $customerId = (int) $paramData['customerId'];
        $campaignId = (int) $paramData['campaignId'];
        $name = $paramData['name'];
        $status = (int) $paramData['status'];

        try {
            $campaignResourceName = ResourceNames::forCampaign($customerId, $campaignId);

            // Constructs an ad group and sets an optional CPC value.
            $adGroup = new AdGroup([
                'campaign' => $campaignResourceName,
                'name' => $name,
                'status' => $status,
                'type' => AdGroupType::SEARCH_STANDARD,
                // 'cpc_bid_micros' => 10000000
            ]);

            $operations = [];
            $adGroupOperation = new AdGroupOperation();
            $adGroupOperation->setCreate($adGroup);
            $operations[] = $adGroupOperation;

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
            return $this->handleGoogleAdsExeption($e);
        }
    }

    public function delete($customerId, $adGroupId)
    {
        try {
            // Creates ad group resource name.
            $adGroupResourceName = ResourceNames::forAdGroup($customerId, $adGroupId);

            // Constructs an operation that will remove the ad group with the specified resource name.
            $adGroupOperation = new AdGroupOperation();
            $adGroupOperation->setRemove($adGroupResourceName);

            // Issues a mutate request to remove the ad group.
            $adGroupServiceClient = $this->googleAdsClient->getAdGroupServiceClient();
            $response = $adGroupServiceClient->mutateAdGroups(
                $customerId,
                [$adGroupOperation]
            );
            
            return Response::json([
                'success' => true,
                'message' => 'deleted ad groups',
            ]);
        } catch (Exception $e) {
            return $this->handleGoogleAdsExeption($e);
        }
    }
}
