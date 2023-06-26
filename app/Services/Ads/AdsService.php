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

    public function list($customerId, $adGroupId)
    {
        try {
            $googleAdsServiceClient = $this->googleAdsClient->getGoogleAdsServiceClient();

            // Creates a query that retrieves responsive search ads.
                $query =
                'SELECT ad_group.id, '
                . 'ad_group_ad.ad.id, '
                . 'ad_group_ad.ad.responsive_search_ad.headlines, '
                . 'ad_group_ad.ad.responsive_search_ad.descriptions, '
                . 'ad_group_ad.status '
                . 'FROM ad_group_ad '
                . 'WHERE ad_group_ad.ad.type = RESPONSIVE_SEARCH_AD '
                . 'AND ad_group_ad.status != "REMOVED"';
            if (!is_null($adGroupId)) {
                $query .= " AND ad_group.id = $adGroupId";
            }

            // Issues a search request by specifying page size.
            $response =
                $googleAdsServiceClient->search($customerId, $query, ['pageSize' => self::PAGE_SIZE]);

            // Iterates over all rows in all pages and prints the requested field values for
            // the responsive search ad in each row.
            $isEmptyResult = true;
            $data = [];
            foreach ($response->iterateAllElements() as $googleAdsRow) {
                $isEmptyResult = false;
                $ad = $googleAdsRow->getAdGroupAd()->getAd();
                $responsiveSearchAdInfo = $ad->getResponsiveSearchAd();

                $data[] = [
                    'resourceName' => $ad->getResourceName(),
                    'status' => AdGroupAdStatus::name($googleAdsRow->getAdGroupAd()->getStatus()),
                    'headlines' => $responsiveSearchAdInfo->getHeadlines(),
                    'descriptions' => $responsiveSearchAdInfo->getDescriptions()

                ];
            }

            // if ($isEmptyResult) {
            //     print 'No responsive search ads were found.' . PHP_EOL;
            // }

            return Response::json([
                'success' => true,
                'data' => $data,
            ]);
            
        } catch (Exception $e) {
            $this->handleGoogleAdsExeption($e);
        }
    }

    public function store($customerId, $adsGroupId)
    {
        try {
            // Creates an ad and sets responsive search ad info.
            $ad = new Ad([
                'responsive_search_ad' => new ResponsiveSearchAdInfo([
                    'headlines' => [
                        // Sets a pinning to always choose this asset for HEADLINE_1. Pinning is
                        // optional; if no pinning is set, then headlines and descriptions will be
                        // rotated and the ones that perform best will be used more often.
                        self::createAdTextAsset(
                            'Cruise to Mars #' . (new DateTime())->format("mdHisv"),
                            ServedAssetFieldType::HEADLINE_1
                        ),
                        self::createAdTextAsset('Best Space Cruise Line'),
                        self::createAdTextAsset('Experience the Stars')
                    ],
                    'descriptions' => [
                        self::createAdTextAsset('Buy your tickets now'),
                        self::createAdTextAsset('Visit the Red Planet')
                    ],
                    'path1' => 'all-inclusive',
                    'path2' => 'deals'
                ]),
                'final_urls' => ['http://www.example.com']
            ]);

            // Creates an ad group ad to hold the above ad.
            $adGroupAd = new AdGroupAd([
                'ad_group' => ResourceNames::forAdGroup($customerId, $adsGroupId),
                'status' => AdGroupAdStatus::PAUSED,
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
            $this->handleGoogleAdsExeption($e);
        }
    }
    
    public function update($customerId, $adId)
    {
        try {
            // Creates an ad with the specified resource name and other changes.
            $ad = new Ad([
                'resource_name' => ResourceNames::forAd($customerId, $adId),
                'responsive_search_ad' => new ResponsiveSearchAdInfo([
                    // Update some properties of the responsive search ad.
                    'headlines' => [
                        new AdTextAsset([
                            'text' => 'Cruise to Pluto #' . (new DateTime())->format("mdHisv"),
                            'pinned_field' => ServedAssetFieldType::HEADLINE_1
                        ]),
                        new AdTextAsset(['text' => 'Tickets on sale now']),
                        new AdTextAsset(['text' => 'Buy your ticket now'])
                    ],
                    'descriptions' => [
                        new AdTextAsset(['text' => 'Best space cruise ever.']),
                        new AdTextAsset([
                            'text' => 'The most wonderful space experience you will ever have.'])
                    ]
                ]),
                'final_urls' => ['http://www.example.com'],
                'final_mobile_urls' => ['http://www.example.com/mobile']
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
            $this->handleGoogleAdsExeption($e);
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
            $this->handleGoogleAdsExeption($e);
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
}
