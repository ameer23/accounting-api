<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
 
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = auth()->user()->transactions()->with('entries')->latest();

        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }
        if ($request->has('account_id')) {
            $query->whereHas('entries', fn ($q) => $q->where('account_id', $request->account_id));
        }
        
        $transactions = $query->paginate(15)->withQueryString();

        return TransactionResource::collection($transactions);
    }


    public function store(StoreTransactionRequest $request): TransactionResource
    {
        $validatedData = $request->validated();

        $transaction = DB::transaction(function () use ($validatedData) {
            $transaction = auth()->user()->transactions()->create([
                'reference' => $validatedData['reference'],
                'description' => $validatedData['description'] ?? null,
                'status' => \App\Enums\TransactionStatus::Posted, 
            ]);

            $transaction->entries()->createMany($validatedData['entries']);

            return $transaction;
        });
        
        return new TransactionResource($transaction->load('entries'));
    }
}