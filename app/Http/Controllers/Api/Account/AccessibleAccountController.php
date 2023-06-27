<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Account\AccountService;

class AccessibleAccountController extends Controller
{
    public function index(AccountService $accountService)
    {
        return $accountService->accessibleAccountlist();
    }

    public function detail(AccountService $accountService)
    {
        $customerId= config('google_ads.customerId');
        return $accountService->detail($customerId);
    }
}
