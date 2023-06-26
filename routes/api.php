<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\GoogleController;
use App\Http\Controllers\Api\Campaign\CampaignController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('auth/google/url', [GoogleController::class, 'loginUrl']);
Route::get('auth/google/callback', [GoogleController::class, 'loginCallback']);

// for google ads
Route::get('auth/google-ads-login-url', [GoogleController::class, 'getGoogleAdsLoginUrl'])->middleware(['auth:sanctum']);
Route::get('auth/generate-google-ads-refresh-token', [GoogleController::class, 'generateGoogleAdsRefreshToken'])->middleware(['auth:sanctum']);


Route::prefix('campaigns')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [CampaignController::class, 'index']);
    Route::post('/store', [CampaignController::class, 'store']);
});