<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $transactions = auth()->user()->transactions()->with('entries')->latest()->paginate(15);

        return response()->json(TransactionResource::collection($transactions));
    }

    
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $transaction = DB::transaction(function () use ($validatedData) {
            $transaction = auth()->user()->transactions()->create([
                'reference' => $validatedData['reference'],
                'description' => $validatedData['description'] ?? null,
            ]);

            $transaction->entries()->createMany($validatedData['entries']);

            return $transaction;
        });

        return response()->json(
            new TransactionResource($transaction->load('entries')),
            201 
        );
    }
}