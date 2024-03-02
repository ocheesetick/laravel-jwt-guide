<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    // Register (POST, formdata)
    public function register(Request $request) {
        // Data validation
        $request->validate([
            "name"  =>  "required",
            "email"  =>  "required|email|unique:users",
            "password"  =>  "required|confirmed"
        ]);


        // Save data
        User::create([
            "name"  =>  $request->name,
            "email" =>  $request->email,
            "password"  =>  Hash::make($request->password)
        ]);

        // Response
        return response()->json([
            "status" => true,
            "message"   =>  "User created successfully"
        ]);
    }

    // Login (POST, formdata)
    public function login(Request $request) {
        // Data validation
        $request->validate([
            "email"  =>  "required|email",
            "password"  =>  "required"
        ]);

        // JWTAuth Attempt
        $token = JWTAuth::attempt([
            'email' => $request->email, 
            'password' => $request->password
        ]);

        // Check if login will enter
        if(!empty($token)) {
            // Response
            return response()->json([
                "status"  =>  true,
                "message"  =>  "User logged in successfully",
                "token"  =>  $token
            ]);
        }

        // If login failed
        return response()->json([
            "status"    =>  false,
            "message"   =>  "Invalid login details"
        ]);
    }

    // Profile (GET)
    public function profile() {
        $userData = auth()->user();

        return response()->json([
            "status"    =>  true,
            "message"   =>  "Profile data",
            "user"  =>  $userData
        ]);
    }

    // Refresh Token API (GET)
    public function refreshToken() {

        // will DELETE existing token and will generate a new one
        $newToken = auth()->refresh();

        return response()->json([
            "status"    =>  true,
            "message"   =>  "New Access Token generated",
            "token"     =>  $newToken
        ]);
    }

    // Logout (GET)
    public function logout() {
        auth()->logout();

        return response()->json([
            "status"    =>  true,
            "message"   =>  "User logged out successfully"
        ]);
    }
}
