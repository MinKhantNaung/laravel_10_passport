<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    // Register API (POST)
    public function register(Request $request)
    {
        // data validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Generate access token
        $token = $user->createToken('Personal Access Token')->accessToken;

        return response()->json([
            'status' => true,
            'token' => $token,
            'message' => 'User registered successfully!',
        ]);
    }

    // Login API (POST)
    public function login(Request $request)
    {
        // data validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = Auth::user();

            $token = $user->createToken('laravel_passport_auth')->accessToken;

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully!',
                'token' => $token
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid login details'
            ]);
        }
    }

    // Profile API (GET)
    public function profile()
    {
        $user = Auth::user();

        return response()->json([
            'status' => true,
            'message' => 'Profile information',
            'data' => $user
        ]);
    }

    // Logout API (POST)
    public function logout()
    {
        auth()->user()->token()->revoke();

        return response()->json([
            "status" => true,
            "message" => "User logged out"
        ]);
    }
}
