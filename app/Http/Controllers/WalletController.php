<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function overview()
    {
        $tt = Transaction::count();
        $tw = 0;
        $twf = Wallet::sum('amount');
        $tu = User::count();
        $twp = Wallet::sum('profit');

        $lt = Transaction::with('user')->orderBy('created_at','DESC')->limit(3)->get();

        // dd(Transaction::where('status',2)->get());
        $at = Transaction::where('status',2)->get()->count();
        $pt = Transaction::where('status',1)->get()->count();

        // $monthlyTransactions = DB::table('transactions')
        // ->selectRaw('DATE_FORMAT(created_at, "%M") as month, COUNT(*) as count')
        // ->groupBy('month')
        // ->get()
        // ->mapWithKeys(function ($item) {
        //     return [
        //         $item->month => $item->count,
        //     ];
        // });

        // // Fill in missing months with 0
        // $allMonths = range(1, 12);
        // foreach ($allMonths as $month) {
        //     if (!isset($monthlyTransactions[$month])) {
        //         $monthlyTransactions[$month] = 0;
        //     }
        // }

        // return $monthlyTransactions;

        return response()->json([
            'total_transaction' => $tt,
            'total_withdrawal' => $tw,
            'total_users' => $tu,
            'total_wallet_fund' => $twf,
            'total_wallet_profit' => $twp,
            'latest_transactions' => $lt,
            'approved_transactions' =>$at,
            'pending_transactions' =>$pt,
        ]);
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
        if (request()->user()->role == 'admin') {
            $wallet->amount = $request->amount ?? $wallet->amount;
            $wallet->profit = $request->profit ?? $wallet->profit;
        }

        $wallet->usdt_wallet_address = $request->usdt_wallet_address ?? $wallet->usdt_wallet_address;
        $wallet->bitcoin_wallet_address = $request->bitcoin_wallet_address ?? $wallet->bitcoin_wallet_address;
        $wallet->solanar_wallet_address = $request->solanar_wallet_address ?? $wallet->solanar_wallet_address;
        $wallet->ethereum_wallet_address = $request->ethereum_wallet_address ?? $wallet->ethereum_wallet_address;
        
        $wallet->save();
        $wallet->refresh();

        return response()->json([
            'message' => 'Wallet information updated successfully',
            'wallet' => $wallet
        ]);
    }

    public function getAdminWallet()
    { 

        $user =  User::with('wallet')->where('role','admin')->first();

        $wallet = Wallet::firstWhere('user_id', $user->id)->only([
            'bitcoin_wallet_address',
            'solanar_wallet_address',
            'ethereum_wallet_address',
            'usdt_wallet_address',
        ]);
        return response()->json(['status' => 'success', 'wallet' => $wallet]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        //
    }
}
