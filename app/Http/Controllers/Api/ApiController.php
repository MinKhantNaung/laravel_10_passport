<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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

    }

    // Profile API (GET)
    public function profile()
    {

    }

    // Logout API (POST)
    public function logout()
    {

    }
}
