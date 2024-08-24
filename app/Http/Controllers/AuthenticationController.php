<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        dd($request->input());
    }

    public function register(Request $request)
    {
        $name = $request->name;
        $gender = $request->gender == 'MALE' ? 1 : 2 ;
        $email = $request->email;
        $phone = $request->phone;
        $raw_password = $request->password;
        $password = 
    }

}
