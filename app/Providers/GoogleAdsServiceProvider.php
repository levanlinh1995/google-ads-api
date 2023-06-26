<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsClientBuilder;

class GoogleAdsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binds the Google Ads API client.
        $this->app->singleton('Google\Ads\GoogleAds\Lib\V14\GoogleAdsClient', function () {
            $user = auth()->user();
            $refreshToken = $user ? $user->gg_ads_refreshtoken : '';

            return (new GoogleAdsClientBuilder())
                ->withDeveloperToken(config('google_ads.developerToken'))
                ->withLoginCustomerId(config('google_ads.clientCustomerId'))
                ->withOAuth2Credential((new OAuth2TokenBuilder())
                    ->withClientId(config('google_ads.clientId'))
                    ->withClientSecret(config('google_ads.clientSecret'))
                    ->withRefreshToken($refreshToken)
                    ->build())
                ->build();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
