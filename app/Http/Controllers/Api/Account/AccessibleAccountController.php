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
        $customerId = 8465198115;
        return $accountService->detail($customerId);
    }
}
