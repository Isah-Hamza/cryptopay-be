<?php

namespace App\Http\Controllers;

use App\Mail\RegistrationMail;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthenticationController extends Controller
{
    public function getAuthUser(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = User::with('wallet','kyc')->find($user_id);
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }


    public function getUsers(Request $request)
    {
        $users = User::with('wallet')->paginate(50);
        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }

    public function getUserById(Request $request,string $id)
    {
        $user = User::with('wallet')->find($id);
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::find($request->user()->id);
        Log::info($request);

        $user->name = $request->name ?? $user->name;
        $user->phone = $request->phone ?? $user->phone;
        $user->gender = $request->gender ?? $user->gender;
        $user->address = $request->address ?? $user->address;

        $user->save();
        $user->refresh();
        
        return response()->json([
            'message' => 'User updated succesfully',
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where('email',  $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) 
           {
                return response()->json([
                    'error' => 'Username or password incorrect',
                ],401);

            }

        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user,
        ]);

    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
          ]);

        // DB::transaction(function() use($user,$validatedData) {
            $new_user = User::create($validatedData);
            $new_wallet = Wallet::create(['user_id' => $new_user->id]);
            $new_user->wallet_id = $new_wallet->id;
            $new_user->save();
            $new_user->refresh();
            $user = $new_user;

            Mail::to($new_user->email)->send(new RegistrationMail($new_user));
            // Mail::to($new_user->email)->send(new RegistrationMail($details));

        // });

        
        return response()->json([
            'message' => 'User created successfully',
            'user' => $new_user
        ]);
    }
    
    public function changePassword(Request $request)
    {
        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) 
           {
                return response()->json([
                    'message' => 'Old password incorrect',
                ],400);
          }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully',
        ]);

    }

}
