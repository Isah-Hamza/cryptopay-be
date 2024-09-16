<?php

namespace App\Http\Controllers;

use App\Mail\WalletFundingMail;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trnx = Transaction::with('user')->paginate(20);
        return response()->json([
            'status' => 'success',
            'data' => $trnx,
        ]);
    }

    public function getUserTransactions(Request $request)
    {
        $trnx = Transaction::where('user_id',$request->user()->id)->paginate(20);
        return response()->json([
            'status' => 'success',
            'data' => $trnx,
        ]);
    }

    public function getTransactionsByUserId(Request $request,string $id)
    {
        $trnx = Transaction::where('user_id',$id)->paginate(20);
        return response()->json([
            'status' => 'success',
            'data' => $trnx,
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

        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'type' => 'numeric',
            'proof' => 'required_if:type,1|string|max:255', 
          ]);

       $trnx = Transaction::create([
            'user_id' => request()->user()->id,
            'amount' => $validatedData['amount'],
            'proof' => $validatedData['proof'] ?? null,
            'transaction_type' => $validatedData['type'] ?? 1,
        ]);
        
        $data = [
            'name' => request()->user()->name,
            'amount' => $request->amount,
            'type' => $trnx->transaction_type,
        ];

        Mail::to(request()->user()->email)->send(new WalletFundingMail($data));


        return response()->json([
            'status' => 'success',
            'data' => $trnx,
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $id)
    {
        $trnx = Transaction::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $trnx,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $trnx = Transaction::find($id);
        $user = User::find($trnx->user_id);
        $user_wallet = Wallet::find($user->wallet_id);

        // $trnx->amount = $request->amount ?? $trnx->amount;
        $trnx->profit = $request->profit ?? $trnx->profit;
        $trnx->status = $request->status ?? $trnx->status;
        $trnx->save();
        $trnx->refresh();


        if($request->profit){
            $user_trnx = Transaction::where('user_id', $user->id)->get();
            $total_profit = $user_trnx->sum('profit');
            $user_wallet->profit = $total_profit;
            $user_wallet->save();
            $user_wallet->refresh();
        }

        if($request->status == '2' && $trnx->computed == 0){
            $wallet_amount = $user_wallet->amount;
            $wallet_amount += $trnx->amount;
            $user_wallet->amount = $wallet_amount;

            $trnx->computed = 1;
            $user_wallet->save();
            $trnx->save();
            
            $trnx->refresh();
            $user_wallet->refresh();
        }

        if($request->status == '3' && $trnx->computed == 1){
            // dd('here');
            $wallet_amount = $user_wallet->amount;
            $wallet_amount -= $trnx->amount;
            $user_wallet->amount = $wallet_amount;

            $trnx_profit = $trnx->profit;
            $user_wallet->profit -= $trnx_profit;
            $trnx->profit = 0;

            $trnx->computed = 0;
            $user_wallet->save();
            $trnx->save();
            
            $trnx->refresh();
            $user_wallet->refresh();
        }

        return response()->json([
            'status' => 'Success',
            'transaction' => $trnx,
            'wallet' => $user_wallet,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        Transaction::find($id)->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Deleted successfully',
        ]);
    }
}
