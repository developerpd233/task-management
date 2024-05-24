<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) : JsonResponse {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['message' => 'Registered successfully', 'user' => $user , 'accessToken' => $token]);
    }

    public function login(LoginRequest $request) : JsonResponse {

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['message' => 'Login successfully', 'user' => $user , 'accessToken' => $token], 200);
    }

    public function me() : JsonResponse {

        $user = auth()->user();
        return response()->json(['user' => $user], 200);
    }

    public function logout() : JsonResponse {

        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully', ], 200);
    }
}
