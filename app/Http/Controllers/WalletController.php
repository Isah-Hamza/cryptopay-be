<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $wallet_id)
    {
        $wallet = Wallet::find($wallet_id);
        $wallet->amount = $request->amount ?? $wallet->amount;
        $wallet->profit = $request->profit ?? $wallet->profit;
        $wallet->usdt_wallet_address = $request->usdt_wallet_address ?? $wallet->profit;
        $wallet->bitcoin_wallet_address = $request->bitcoin_wallet_address ?? $wallet->profit;
        $wallet->solanar_wallet_address = $request->solanar_wallet_address ?? $wallet->profit;
        $wallet->save();

        return response()->json([
            'message'=>'Wallet information updated successfully',
            'wallet' => $wallet
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        //
    }
}
