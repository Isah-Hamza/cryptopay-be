<?php

namespace App\Http\Controllers;

use App\Jobs\ApproveKyc;
use App\Mail\KYCSubmittedMail;
use App\Models\Kyc;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class KycController extends Controller
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

        $user = User::find($request->user()->id);

        if($user->email != $request->email){
            return response()->json(['error'=>"The supplied email is different from your login email"],401);
        }
        if($user->phone != $request->phone){
            return response()->json(['error'=>"The supplied phone number is different from your user phone number"],401);
        }

        if(!Hash::check($request->password, $user->password)){
            return response()->json(['error'=>"Incorrect login password supplied. Please check again."],401);
        }

        // return ($this);

        $record = Kyc::updateOrCreate([
            'user_id' => $user->id,
        ],[
            'user_id' => $user->id,
            'means_of_identification' => $request->means_of_identification,
            'identification_number' => $request->identification_number,
            'status' => 1,
        ]);

        Mail::to($request->email)->send(new KYCSubmittedMail($user));
        ApproveKyc::dispatch($record);
        // ->delay(now()->addMinute(1));


        return response()->json(['message' => 'KYC status updated successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Kyc $kyc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kyc $kyc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kyc $kyc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kyc $kyc)
    {
        //
    }
}
