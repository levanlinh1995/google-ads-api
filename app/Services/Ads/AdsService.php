<?php

namespace App\Services\Ads;

use GetOpt\GetOpt;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentNames;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentParser;
use Google\Ads\GoogleAds\Examples\Utils\Helper;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsException;
use Google\Ads\GoogleAds\Util\V14\ResourceNames;
use Google\Ads\GoogleAds\V14\Common\AdTextAsset;
use Google\Ads\GoogleAds\V14\Common\ResponsiveSearchAdInfo;
use Google\Ads\GoogleAds\V14\Enums\AdGroupAdStatusEnum\AdGroupAdStatus;
use Google\Ads\GoogleAds\V14\Enums\ServedAssetFieldTypeEnum\ServedAssetFieldType;
use Google\Ads\GoogleAds\V14\Errors\GoogleAdsError;
use Google\Ads\GoogleAds\V14\Resources\Ad;
use Google\Ads\GoogleAds\V14\Resources\AdGroupAd;
use Google\Ads\GoogleAds\V14\Services\AdGroupAdOperation;
use Google\ApiCore\ApiException;
use App\Classes\GoogleAdsOauth;
use Google\Ads\GoogleAds\V14\Services\AdOperation;
use Google\Ads\GoogleAds\Util\FieldMasks;
use DateTime;
use Google\Protobuf\Internal\RepeatedField;
use Illuminate\Support\Facades\Response;
use App\Services\BaseService;
use Exception;

class AdsService extends BaseService
{
    private GoogleAdsClient $googleAdsClient;

    private const PAGE_SIZE = 1000;

    public function __construct(GoogleAdsClient $googleAdsClient)
    {
        $this->googleAdsClient = $googleAdsClient;
    }

    public function list($customerId, $adGroupId = null)
    {
        try {
            $googleAdsServiceClient = $this->googleAdsClient->getGoogleAdsServiceClient();

            // Creates a query that retrieves responsive search ads.
            $query = "
            SELECT 
                ad_group_ad.ad.final_urls, 
                ad_group_ad.ad.id, 
                ad_group_ad.ad.name, 
                ad_group_ad.ad.resource_name, 
                ad_group_ad.ad.responsive_search_ad.descriptions, 
                ad_group_ad.ad.responsive_search_ad.headlines, 
                ad_group_ad.ad.type, 
                ad_group_ad.ad_group, 
                ad_group_ad.resource_name, 
                ad_group_ad.status, 
                campaign.advertising_channel_type, 
                campaign.bidding_strategy, 
                campaign.campaign_group, 
                campaign.campaign_budget, 
                campaign.bidding_strategy_type, 
                campaign.end_date, 
                campaign.id, 
                campaign.listing_type, 
                campaign.manual_cpa, 
                campaign.name, 
                campaign.optimization_score, 
                campaign.resource_name, 
                campaign.start_date, 
                campaign.status, 
                customer.currency_code, 
                customer.descriptive_name, 
                customer.id, 
                customer.manager, 
                customer.resource_name, 
                customer.status, 
                customer.time_zone, 
                customer.test_account, 
                ad_group.campaign, 
                ad_group.id, 
                ad_group.name, 
                ad_group.resource_name, 
                ad_group.status, 
                ad_group.type 
            FROM ad_group_ad 
            WHERE ad_group_ad.ad.type = 'RESPONSIVE_SEARCH_AD' 
                AND ad_group_ad.status IN ('ENABLED', 'PAUSED') 
                AND campaign.status != 'REMOVED' 
                AND ad_group.status != 'REMOVED' 
            ";

            if (!empty($adGroupId)) {
                $query .= " AND ad_group.id = $adGroupId";
            }

            // Issues a search request by specifying page size.
            $response =
                $googleAdsServiceClient->search($customerId, $query, ['pageSize' => self::PAGE_SIZE]);

            // Iterates over all rows in all pages and prints the requested field values for
            // the responsive search ad in each row.
            $data = [];
            foreach ($response->iterateAllElements() as $googleAdsRow) {
                $ad = $googleAdsRow->getAdGroupAd()->getAd();
                $adGroupAd = $googleAdsRow->getAdGroupAd();
                $adGroup = $googleAdsRow->getAdGroup();
                $campaign = $googleAdsRow->getCampaign();
                $customer = $googleAdsRow->getCustomer();
                $responsiveSearchAdInfo = $ad->getResponsiveSearchAd();

                $data[] = [
                    'ad_resource_name' => $ad->getResourceName(),
                    'ad_id' => $ad->getId(),
                    'ad_name' => $ad->getName(),
                    'ad_final_urls' => $ad->getFinalUrls(),
                    'ad_group_ad_status' => $adGroupAd->getStatus(),
                    'ad_group_ad_status_name' => AdGroupAdStatus::name($adGroupAd->getStatus()),
                    'headlines' => self::convertAdTextAssetsToArray($responsiveSearchAdInfo->getHeadlines()),
                    'descriptions' => self::convertAdTextAssetsToArray($responsiveSearchAdInfo->getDescriptions()),
                    'account_id' => $customer->getId(),
                    'account_name' => $customer->getDescriptiveName(),
                    'campaign_id' => $campaign->getId(),
                    'campaign_name' => $campaign->getName(),
                    'ad_group_id' => $adGroup->getId(),
                    'ad_group_name' => $adGroup->getName(),
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

    public function detail($customerId, $adId, $adGroupId = null)
    {
        try {
            $googleAdsServiceClient = $this->googleAdsClient->getGoogleAdsServiceClient();

            // Creates a query that retrieves responsive search ads.
            $query = "
            SELECT 
                ad_group_ad.ad.final_urls, 
                ad_group_ad.ad.id, 
                ad_group_ad.ad.name, 
                ad_group_ad.ad.resource_name, 
                ad_group_ad.ad.responsive_search_ad.descriptions, 
                ad_group_ad.ad.responsive_search_ad.headlines, 
                ad_group_ad.ad.type, 
                ad_group_ad.ad_group, 
                ad_group_ad.resource_name, 
                ad_group_ad.status, 
                campaign.advertising_channel_type, 
                campaign.bidding_strategy, 
                campaign.campaign_group, 
                campaign.campaign_budget, 
                campaign.bidding_strategy_type, 
                campaign.end_date, 
                campaign.id, 
                campaign.listing_type, 
                campaign.manual_cpa, 
                campaign.name, 
                campaign.optimization_score, 
                campaign.resource_name, 
                campaign.start_date, 
                campaign.status, 
                customer.currency_code, 
                customer.descriptive_name, 
                customer.id, 
                customer.manager, 
                customer.resource_name, 
                customer.status, 
                customer.time_zone, 
                customer.test_account, 
                ad_group.campaign, 
                ad_group.id, 
                ad_group.name, 
                ad_group.resource_name, 
                ad_group.status, 
                ad_group.type 
            FROM ad_group_ad 
            WHERE ad_group_ad.ad.type = 'RESPONSIVE_SEARCH_AD' 
                AND ad_group_ad.status IN ('ENABLED', 'PAUSED') 
                AND campaign.status != 'REMOVED' 
                AND ad_group.status != 'REMOVED' 
                AND ad_group_ad.ad.id = $adId 
            LIMIT 1 
            ";


            if (!empty($adGroupId)) {
                $query .= " AND ad_group.id = $adGroupId";
            }

            // Issues a search request by specifying page size.
            $response =
                $googleAdsServiceClient->search($customerId, $query);

            // Iterates over all rows in all pages and prints the requested field values for
            // the responsive search ad in each row.
            $data = [];
            foreach ($response->iterateAllElements() as $googleAdsRow) {
                $ad = $googleAdsRow->getAdGroupAd()->getAd();
                $adGroupAd = $googleAdsRow->getAdGroupAd();
                $adGroup = $googleAdsRow->getAdGroup();
                $campaign = $googleAdsRow->getCampaign();
                $customer = $googleAdsRow->getCustomer();
                $responsiveSearchAdInfo = $ad->getResponsiveSearchAd();

                $data[] = [
                    'ad_resource_name' => $ad->getResourceName(),
                    'ad_id' => $ad->getId(),
                    'ad_name' => $ad->getName(),
                    'ad_final_urls' => $ad->getFinalUrls(),
                    'ad_group_ad_status' => $adGroupAd->getStatus(),
                    'ad_group_ad_status_name' => AdGroupAdStatus::name($adGroupAd->getStatus()),
                    'headlines' => self::convertAdTextAssetsToArray($responsiveSearchAdInfo->getHeadlines()),
                    'descriptions' => self::convertAdTextAssetsToArray($responsiveSearchAdInfo->getDescriptions()),
                    'account_id' => $customer->getId(),
                    'account_name' => $customer->getDescriptiveName(),
                    'campaign_id' => $campaign->getId(),
                    'campaign_name' => $campaign->getName(),
                    'ad_group_id' => $adGroup->getId(),
                    'ad_group_name' => $adGroup->getName(),
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

    public function store($customerId, $paramData)
    {
        $adsGroupId = $paramData['adsGroupId'];
        $name = $paramData['name'];
        $status = (int) $paramData['status'];
        $headline1 = $paramData['headline1'];
        $headline2 = $paramData['headline2'];
        $headline3 = $paramData['headline3'];
        $description1 = $paramData['description1'];
        $description2 = $paramData['description2'];
        $url = $paramData['url'];

        try {
            // Creates an ad and sets responsive search ad info.
            $ad = new Ad([
                'name' => $name,
                'responsive_search_ad' => new ResponsiveSearchAdInfo([
                    'headlines' => [
                        // Sets a pinning to always choose this asset for HEADLINE_1. Pinning is
                        // optional; if no pinning is set, then headlines and descriptions will be
                        // rotated and the ones that perform best will be used more often.
                        self::createAdTextAsset(
                            $headline1,
                            ServedAssetFieldType::HEADLINE_1
                        ),
                        self::createAdTextAsset(
                            $headline2,
                            ServedAssetFieldType::HEADLINE_2
                        ),
                        self::createAdTextAsset(
                            $headline3,
                            ServedAssetFieldType::HEADLINE_3
                        ),
                    ],
                    'descriptions' => [
                        self::createAdTextAsset(
                            $description1,
                            ServedAssetFieldType::DESCRIPTION_1
                        ),
                        self::createAdTextAsset(
                            $description2,
                            ServedAssetFieldType::DESCRIPTION_2
                        ),
                    ]
                ]),
                'final_urls' => [
                    // self::createAdTextAsset(
                    //     $url,
                    //     ServedAssetFieldType::SITELINK
                    // )
                    $url,
                ]
            ]);

            // Creates an ad group ad to hold the above ad.
            $adGroupAd = new AdGroupAd([
                'ad_group' => ResourceNames::forAdGroup($customerId, $adsGroupId),
                'status' => $status,
                'ad' => $ad
            ]);

            // Creates an ad group ad operation.
            $adGroupAdOperation = new AdGroupAdOperation();
            $adGroupAdOperation->setCreate($adGroupAd);

            // Issues a mutate request to add the ad group ad.
            $adGroupAdServiceClient = $this->googleAdsClient->getAdGroupAdServiceClient();
            $response = $adGroupAdServiceClient->mutateAdGroupAds($customerId, [$adGroupAdOperation]);

            $createdAdGroupAdResourceName = $response->getResults()[0]->getResourceName();

            return Response::json([
                'success' => true,
                'message' => "Created responsive search ad with resource name " . $createdAdGroupAdResourceName
            ]);
            
        } catch (Exception $e) {
            return $this->handleGoogleAdsExeption($e);
        }
    }
    
    public function update($customerId, $adId, $paramData)
    {
        $name = $paramData['name'];
        // $status = (int) $paramData['status'];
        $headline1 = $paramData['headline1'];
        $headline2 = $paramData['headline2'];
        $headline3 = $paramData['headline3'];
        $description1 = $paramData['description1'];
        $description2 = $paramData['description2'];
        $url = $paramData['url'];

        try {
            // Creates an ad with the specified resource name and other changes.
            $ad = new Ad([
                'resource_name' => ResourceNames::forAd($customerId, (int) $adId),
                'name' => $name,
                'responsive_search_ad' => new ResponsiveSearchAdInfo([
                    // Update some properties of the responsive search ad.
                    'headlines' => [
                        self::createAdTextAsset(
                            $headline1,
                            ServedAssetFieldType::HEADLINE_1
                        ),
                        self::createAdTextAsset(
                            $headline2,
                            ServedAssetFieldType::HEADLINE_2
                        ),
                        self::createAdTextAsset(
                            $headline3,
                            ServedAssetFieldType::HEADLINE_3
                        ),
                    ],
                    'descriptions' => [
                        self::createAdTextAsset(
                            $description1,
                            ServedAssetFieldType::DESCRIPTION_1
                        ),
                        self::createAdTextAsset(
                            $description2,
                            ServedAssetFieldType::DESCRIPTION_2
                        ),
                    ]
                ]),
                'final_urls' => [$url],
            ]);

            // Constructs an operation that will update the ad, using the FieldMasks to derive the
            // update mask. This mask tells the Google Ads API which attributes of the ad you want to
            // change.
            $adOperation = new AdOperation();
            $adOperation->setUpdate($ad);
            $adOperation->setUpdateMask(FieldMasks::allSetFieldsOf($ad));

            // Issues a mutate request to update the ad.
            $adServiceClient = $this->googleAdsClient->getAdServiceClient();
            $response = $adServiceClient->mutateAds($customerId, [$adOperation]);

            // Prints the resource name of the updated ad.
            $updatedAd = $response->getResults()[0];

            return Response::json([
                'success' => true,
                'message' => "Updated ad with resource name " . $updatedAd->getResourceName()
            ]);
            
        } catch (Exception $e) {
            return $this->handleGoogleAdsExeption($e);
        }
    }

    public function delete($customerId, $adGroupId, $adId)
    {
        try {
            // Creates ad group ad resource name.
            $adGroupAdResourceName = ResourceNames::forAdGroupAd($customerId, $adGroupId, $adId);

            // Constructs an operation that will remove the ad with the specified resource name.
            $adGroupAdOperation = new AdGroupAdOperation();
            $adGroupAdOperation->setRemove($adGroupAdResourceName);

            // Issues a mutate request to remove the ad group ad.
            $adGroupAdServiceClient = $this->googleAdsClient->getAdGroupAdServiceClient();
            $response = $adGroupAdServiceClient->mutateAdGroupAds(
                $customerId,
                [$adGroupAdOperation]
            );

            // Prints the resource name of the removed ad group ad.
            $removedAdGroupAd = $response->getResults()[0];

            return Response::json([
                'success' => true,
                'message' => "Removed ad group ad with resource name " . $removedAdGroupAd->getResourceName()
            ]);
        } catch (Exception $e) {
            return $this->handleGoogleAdsExeption($e);
        }
    }

    /**
     * Creates an ad text asset with the specified text and pin field enum value.
     *
     * @param string $text the text to be set
     * @param int|null $pinField the enum value of the pin field
     * @return AdTextAsset the created ad text asset
     */
    private static function createAdTextAsset(string $text, int $pinField = null): AdTextAsset
    {
        $adTextAsset = new AdTextAsset(['text' => $text]);
        if (!is_null($pinField)) {
            $adTextAsset->setPinnedField($pinField);
        }
        return $adTextAsset;
    }

    /**
     * Converts the list of AdTextAsset objects into a string representation.
     *
     * @param RepeatedField $assets the list of AdTextAsset objects
     * @return string the string representation of the provided list of AdTextAsset objects
     */
    private static function convertAdTextAssetsToString(RepeatedField $assets): string
    {
        $result = '';
        foreach ($assets as $asset) {
            /** @var AdTextAsset $asset */
            $result .= sprintf(
                "\t%s pinned to %s.%s",
                $asset->getText(),
                ServedAssetFieldType::name($asset->getPinnedField()),
                PHP_EOL
            );
        }
        return $result;
    }

    private static function convertAdTextAssetsToArray(RepeatedField $assets): array
    {
        $result = [];
        foreach ($assets as $asset) {
            $result[] = [
                ServedAssetFieldType::name($asset->getPinnedField()) => $asset->getText()
            ];
        }
        return $result;
    }
}
