<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google\Ads\GoogleAds\Lib\V14\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binds the Google Ads API client.
        $this->app->singleton('Google\Ads\GoogleAds\Lib\V14\GoogleAdsClient', function () {
            return (new GoogleAdsClientBuilder())
                ->withDeveloperToken(config('google_ads.developerToken'))
                ->withLoginCustomerId(config('google_ads.clientCustomerId'))
                ->withOAuth2Credential((new OAuth2TokenBuilder())
                    ->withClientId(config('google_ads.clientId'))
                    ->withClientSecret(config('google_ads.clientSecret'))
                    ->withRefreshToken(config('google_ads.refreshToken'))
                    ->build())
                ->build();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
