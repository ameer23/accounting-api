<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\Entry;
use Illuminate\Http\JsonResponse;
use App\Enums\AccountType;

class AccountController extends Controller
{
   
    public function index(): JsonResponse
    {
        $accounts = Account::all();
        return response()->json(AccountResource::collection($accounts));
    }

   
    public function store(StoreAccountRequest $request): JsonResponse
    {
        $account = Account::create($request->validated());
        return response()->json(new AccountResource($account), 201);
    }

    
    public function balance(Account $account): JsonResponse
    {
        $debits = Entry::where('account_id', $account->id)->where('type', 'debit')->sum('amount');
        $credits = Entry::where('account_id', $account->id)->where('type', 'credit')->sum('amount');

        $balance = 0;

        switch ($account->type) {
            case AccountType::Asset:
            case AccountType::Expense:
                $balance = $debits - $credits;
                break;

            case AccountType::Liability:
            case AccountType::Equity:
            case AccountType::Income:
                $balance = $credits - $debits;
                break;
        }

        return response()->json([
            'balance' => number_format($balance, 2, '.', ''),
            'account_type' => $account->type->value,
            'account_name' => $account->name
        ]);
    }
}