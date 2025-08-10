<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
   
    public function index(): JsonResponse
    {
        $accounts = Account::all();

        return response()->json(AccountResource::collection($accounts));
    }

   
    public function store(StoreAccountRequest $request): AccountResource
    {
        $account = Account::create($request->validated());

        return new AccountResource($account);
    }

    
    public function balance(Account $account): JsonResponse
    {
         return response()->json([
            'balance' => $account->balance, 
            'account_name' => $account->name
        ]);
    }
}