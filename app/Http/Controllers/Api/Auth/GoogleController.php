<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;
use Google\Auth\OAuth2;
use Google\Auth\CredentialsLoader;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function loginUrl()
    {
        return Response::json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function loginCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = null;

        DB::transaction(function () use ($googleUser, &$user) {
            $socialAccount = SocialAccount::firstOrNew(
                ['social_id' => $googleUser->getId(), 'social_provider' => 'google'],
                ['social_name' => $googleUser->getName()]
            );

            if (!($user = $socialAccount->user)) {
                $user = User::create([
                    'email' => $googleUser->getEmail(),
                    'name' => $googleUser->getName(),
                ]);
                $socialAccount->fill(['user_id' => $user->id])->save();
            }
        });

        return Response::json([
            'accessToken' => $user->createToken("login_token")->plainTextToken,
            'user' => new UserResource($user),
        ]);
    }

    private function initializeOauth2()
    {
        $clientId = config('google_ads.clientId');
        $clientSecret = config('google_ads.clientSecret');
        $redirectUrl = config('google_ads.redirectUri');
        $scope = config('google_ads.scope');
        $authorizationUrl = config('google_ads.authorizationUri');

        $oauth2 = new OAuth2(
            [
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
                'authorizationUri' => $authorizationUrl,
                'redirectUri' => $redirectUrl,
                'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
                'scope' => $scope,
                'state' => sha1(openssl_random_pseudo_bytes(1024))
            ]
        );

        return $oauth2;
    }

    function getGoogleAdsLoginUrl()
    {
        $oauth2 = $this->initializeOauth2();

        $googleAdsLoginUrl = $oauth2->buildFullAuthorizationUri();

        return Response::json([
            'url' => $googleAdsLoginUrl
        ]);
    }

    function generateGoogleAdsRefreshToken(Request $request)
    {
        $oauth2 = $this->initializeOauth2();

        $code = $request->get('token');

        $oauth2->setCode($code);
        $authToken = $oauth2->fetchAuthToken();

        $refreshToken = $authToken['refresh_token'];
        config('google_ads.refreshToken', $refreshToken);

        return Response::json([
            'success' => true
        ]);
    }
}
