<?php

namespace App\Services\Account;

use DateTime;
use App\Services\BaseService;
use Illuminate\Support\Facades\Response;
use Exception;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsException;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\V14\Errors\GoogleAdsError;

class AccountService extends BaseService
{
    private GoogleAdsClient $googleAdsClient;

    public function __construct(GoogleAdsClient $googleAdsClient)
    {
        $this->googleAdsClient = $googleAdsClient;
    }

    public function accessibleAccountlist()
    {
        try {
            $customerServiceClient = $this->googleAdsClient->getCustomerServiceClient();

            // Issues a request for listing all accessible customers.
            $accessibleCustomers = $customerServiceClient->listAccessibleCustomers();

            // Iterates over all accessible customers' resource names and prints them.
            $data = [];
            foreach ($accessibleCustomers->getResourceNames() as $resourceName) {
                // resource_name: 'customers/4006031100'
                $data[] = [
                    'resource_name' => $resourceName
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

    public function detail($customerId)
    {
        $data = $this->getdetailData($customerId);
        return Response::json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function getdetailData($customerId)
    {
        try {
            // Creates a query that retrieves the specified customer.
            $query = 'SELECT customer.id, '
            . 'customer.descriptive_name, '
            . 'customer.currency_code, '
            . 'customer.time_zone, '
            . 'customer.tracking_url_template, '
            . 'customer.auto_tagging_enabled '
            . 'FROM customer '
            // Limits to 1 to clarify that selecting from the customer resource will always return
            // only one row, which will be for the customer ID specified in the request.
            . 'LIMIT 1';
        // Issues a search request to get the Customer object from the single row of the response
        $googleAdsServiceClient = $this->googleAdsClient->getGoogleAdsServiceClient();

        $customer = $googleAdsServiceClient->search($customerId, $query)
            ->getIterator()
            ->current()
            ->getCustomer();

        // Print information about the account.
        printf(
            "Customer with ID %d, descriptive name '%s', currency code '%s', timezone '%s', "
            . "tracking URL template '%s' and auto tagging enabled '%s' was retrieved.%s",
            $customer->getId(),
            $customer->getDescriptiveName(),
            $customer->getCurrencyCode(),
            $customer->getTimeZone(),
            is_null($customer->getTrackingUrlTemplate())
                ? 'N/A' : $customer->getTrackingUrlTemplate(),
            $customer->getAutoTaggingEnabled() ? 'true' : 'false',
            PHP_EOL
        );

        return [
            'customer_id' => $customer->getId(),
            'descriptive_name' => $customer->getId(),
            'currency_code' => $customer->getCurrencyCode(),
            'timezone' => $customer->getTimeZone(),
        ];

        } catch (Exception $e) {
            return $this->handleGoogleAdsExeption($e);
        }
    }
}
