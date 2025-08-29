<?php

namespace App\Http\Controllers\Website\Auth;

use App\Http\Controllers\Controller;
use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{

    public function register(Request $request)
    {
        
        try {
            $request->validate([
                'fullname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:web_users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = WebUser::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Log the user in via web guard
            Auth::guard('webuser')->login($user);
            
            // Create a new token for API authentication
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'redirect' => '/'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
